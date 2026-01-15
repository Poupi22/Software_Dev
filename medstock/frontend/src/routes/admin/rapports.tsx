import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { PanelCard } from "@/components/admin/cards";
import { Loader2, FileDown, FileSpreadsheet } from "lucide-react";
import { toast } from "sonner";
import { ventesApi } from "@/lib/ventes";
import { medicamentsApi } from "@/lib/medicaments";
import {
  LineChart, Line, BarChart, Bar, XAxis, YAxis, Tooltip, 
  ResponsiveContainer, CartesianGrid, PieChart, Pie, Cell, Legend
} from "recharts";
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export const Route = createFileRoute("/admin/rapports")({
  component: RapportsPage,
});

const COLORS = ["#3b82f6", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6", "#ec4899", "#06b6d4", "#84cc16"];

interface VenteQuotidienne {
  jour: string;
  ventes: number;
  total: number;
}

interface RevenuMensuel {
  mois: string;
  revenu: number;
}

interface TopMedicament {
  nom: string;
  ventes: number;
  total: number;
}

function RapportsPage() {
  const [loading, setLoading] = useState(true);
  const [ventesQuotidiennes, setVentesQuotidiennes] = useState<VenteQuotidienne[]>([]);
  const [revenusMensuels, setRevenusMensuels] = useState<RevenuMensuel[]>([]);
  const [topMedicaments, setTopMedicaments] = useState<TopMedicament[]>([]);

  useEffect(() => {
    fetchRapports();
  }, []);

  const fetchRapports = async () => {
    setLoading(true);
    try {
      const ventesRes = await ventesApi.getAll();
      const ventes = ventesRes.data.data;
      
      const medicamentsRes = await medicamentsApi.getAll();
      const medicaments = medicamentsRes.data.data;
      
      const last7Days = Array.from({ length: 7 }, (_, i) => {
        const d = new Date();
        d.setDate(d.getDate() - i);
        return d.toISOString().split('T')[0];
      }).reverse();
      
      const ventesParJour = last7Days.map(jour => {
        const ventesDuJour = ventes.filter((v: any) => v.created_at.split('T')[0] === jour);
        return {
          jour: new Date(jour).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' }),
          ventes: ventesDuJour.length,
          total: ventesDuJour.reduce((sum: number, v: any) => sum + Number(v.total), 0)
        };
      });
      setVentesQuotidiennes(ventesParJour);
      
      const last6Months = Array.from({ length: 6 }, (_, i) => {
        const d = new Date();
        d.setMonth(d.getMonth() - i);
        return { year: d.getFullYear(), month: d.getMonth(), label: d.toLocaleDateString('fr-FR', { month: 'short' }) };
      }).reverse();
      
      const revenusParMois = last6Months.map(({ year, month, label }) => {
        const ventesDuMois = ventes.filter((v: any) => {
          const date = new Date(v.created_at);
          return date.getFullYear() === year && date.getMonth() === month;
        });
        return {
          mois: label,
          revenu: ventesDuMois.reduce((sum: number, v: any) => sum + Number(v.total), 0)
        };
      });
      setRevenusMensuels(revenusParMois);
      
      const ventesParMedicament: Record<string, { nom: string; ventes: number; total: number }> = {};
      
      for (const vente of ventes) {
        try {
          const detailRes = await ventesApi.getById(vente.id);
          const lignes = detailRes.data.data.lignes || [];
          
          for (const ligne of lignes) {
            const medId = ligne.medicament_id;
            const med = medicaments.find((m: any) => m.id === medId);
            const nom = ligne.nom_snapshot || med?.nom || 'Inconnu';
            const quantite = typeof ligne.quantite === 'string' ? parseInt(ligne.quantite) : ligne.quantite;
            const totalLigne = typeof ligne.total_ligne === 'string' ? parseFloat(ligne.total_ligne) : ligne.total_ligne;
            
            if (!ventesParMedicament[medId]) {
              ventesParMedicament[medId] = { nom, ventes: 0, total: 0 };
            }
            ventesParMedicament[medId].ventes += quantite;
            ventesParMedicament[medId].total += totalLigne;
          }
        } catch (e) {
          console.error("Erreur récupération détails vente:", vente.id);
        }
      }
      
      const top5 = Object.values(ventesParMedicament)
        .sort((a, b) => b.ventes - a.ventes)
        .slice(0, 5);
      setTopMedicaments(top5);
      
    } catch (error) {
      console.error("Error fetching rapports:", error);
      toast.error("Erreur lors du chargement des rapports");
    } finally {
      setLoading(false);
    }
  };

  const formatPrice = (price: number) => {
    if (isNaN(price)) return "0 FCFA";
    return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
  };

  const exportPDF = () => {
    try {
      const doc = new jsPDF();
      doc.setFontSize(20);
      doc.text("Rapport PharmaCare", 14, 20);
      doc.setFontSize(12);
      doc.text(`Genere le ${new Date().toLocaleDateString('fr-FR')}`, 14, 30);
      
      doc.setFontSize(14);
      doc.text("Ventes journalieres (7 derniers jours)", 14, 45);
      
      const ventesData = ventesQuotidiennes.map(v => [v.jour, v.ventes.toString(), formatPrice(v.total)]);
      autoTable(doc, {
        startY: 50,
        head: [["Date", "Nombre de ventes", "Total"]],
        body: ventesData,
        foot: [["Total", ventesQuotidiennes.reduce((s, v) => s + v.ventes, 0).toString(), formatPrice(ventesQuotidiennes.reduce((s, v) => s + v.total, 0))]],
      });
      
      let y = (doc as any).lastAutoTable?.finalY + 15 || 100;
      doc.text("Revenus mensuels (6 derniers mois)", 14, y);
      
      const revenusData = revenusMensuels.map(r => [r.mois, formatPrice(r.revenu)]);
      autoTable(doc, {
        startY: y + 10,
        head: [["Mois", "Revenu"]],
        body: revenusData,
        foot: [["Total", formatPrice(revenusMensuels.reduce((s, r) => s + r.revenu, 0))]],
      });
      
      y = (doc as any).lastAutoTable?.finalY + 15 || 200;
      doc.text("Top 5 medicaments les plus vendus", 14, y);
      
      const topData = topMedicaments.map(m => [m.nom, m.ventes.toString(), formatPrice(m.total)]);
      autoTable(doc, {
        startY: y + 10,
        head: [["Medicament", "Quantite vendue", "Chiffre d'affaires"]],
        body: topData,
      });
      
      doc.save(`rapport_pharmacare_${new Date().toISOString().split('T')[0]}.pdf`);
      toast.success("PDF exporte avec succes");
    } catch (error) {
      console.error("PDF export error:", error);
      toast.error("Erreur lors de l'export PDF");
    }
  };

  const exportExcel = () => {
    try {
      const workbook = XLSX.utils.book_new();
      
      const ventesSheet = XLSX.utils.json_to_sheet(
        ventesQuotidiennes.map(v => ({
          Date: v.jour,
          "Nombre de ventes": v.ventes,
          "Total (FCFA)": v.total
        }))
      );
      XLSX.utils.book_append_sheet(workbook, ventesSheet, "Ventes journalieres");
      
      const revenusSheet = XLSX.utils.json_to_sheet(
        revenusMensuels.map(r => ({
          Mois: r.mois,
          "Revenu (FCFA)": r.revenu
        }))
      );
      XLSX.utils.book_append_sheet(workbook, revenusSheet, "Revenus mensuels");
      
      const topSheet = XLSX.utils.json_to_sheet(
        topMedicaments.map(m => ({
          Medicament: m.nom,
          "Quantite vendue": m.ventes,
          "Chiffre d'affaires (FCFA)": m.total
        }))
      );
      XLSX.utils.book_append_sheet(workbook, topSheet, "Top medicaments");
      
      XLSX.writeFile(workbook, `rapport_pharmacare_${new Date().toISOString().split('T')[0]}.xlsx`);
      toast.success("Excel exporte avec succes");
    } catch (error) {
      console.error("Excel export error:", error);
      toast.error("Erreur lors de l'export Excel");
    }
  };

  const total7Jours = ventesQuotidiennes.reduce((sum, d) => sum + (isNaN(d.total) ? 0 : d.total), 0);
  const total6Mois = revenusMensuels.reduce((sum, m) => sum + (isNaN(m.revenu) ? 0 : m.revenu), 0);

  const CustomTooltip = ({ active, payload, label }: any) => {
    if (active && payload && payload.length) {
      return (
        <div className="rounded-lg border bg-white p-3 shadow-lg">
          <p className="text-sm font-semibold">{label}</p>
          <p className="text-sm text-primary">
            {payload[0].name === "ventes" 
              ? `${payload[0].value} ventes` 
              : formatPrice(payload[0].value)}
          </p>
        </div>
      );
    }
    return null;
  };

  const CustomBarTooltip = ({ active, payload, label }: any) => {
    if (active && payload && payload.length) {
      return (
        <div className="rounded-lg border bg-white p-3 shadow-lg">
          <p className="text-sm font-semibold">{label}</p>
          <p className="text-sm text-primary">{formatPrice(payload[0].value)}</p>
        </div>
      );
    }
    return null;
  };

  const CustomPieTooltip = ({ active, payload }: any) => {
    if (active && payload && payload.length) {
      const data = payload[0].payload;
      return (
        <div className="rounded-lg border bg-white p-3 shadow-lg">
          <p className="text-sm font-semibold">{data.nom}</p>
          <p className="text-sm text-primary">{data.ventes} vendus</p>
          <p className="text-xs text-muted-foreground">{formatPrice(data.total)}</p>
        </div>
      );
    }
    return null;
  };

  const renderCustomLabel = (entry: any) => {
    const percent = (entry.percent ?? 0) * 100;
    return `${entry.nom}: ${percent.toFixed(0)}%`;
  };

  if (loading) {
    return (
      <AdminLayout>
        <div className="flex h-96 items-center justify-center">
          <Loader2 className="h-8 w-8 animate-spin text-primary" />
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <PageHeader
        title="Rapports & Analytics"
        description="Analyse detaillee de l'activite de la pharmacie"
        actions={
          <div className="flex items-center gap-2">
            <button onClick={exportPDF} className="flex h-10 items-center gap-2 rounded-lg border bg-card px-4 text-sm font-medium hover:bg-muted">
              <FileDown className="h-4 w-4" /> PDF
            </button>
            <button onClick={exportExcel} className="flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground">
              <FileSpreadsheet className="h-4 w-4" /> Excel
            </button>
          </div>
        }
      />

      <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <PanelCard title="Ventes journalieres (7 derniers jours)">
          <ResponsiveContainer width="100%" height={260}>
            <LineChart data={ventesQuotidiennes}>
              <CartesianGrid strokeDasharray="3 3" vertical={false} />
              <XAxis dataKey="jour" fontSize={11} tickLine={false} axisLine={false} />
              <YAxis fontSize={11} tickLine={false} axisLine={false} />
              <Tooltip content={<CustomTooltip />} />
              <Line type="monotone" dataKey="ventes" stroke="#3b82f6" strokeWidth={3} dot={{ r: 4, fill: "#3b82f6" }} />
            </LineChart>
          </ResponsiveContainer>
          <div className="mt-2 text-center text-xs text-muted-foreground">
            Total 7 jours: {formatPrice(total7Jours)}
          </div>
        </PanelCard>

        <PanelCard title="Revenus mensuels">
          <ResponsiveContainer width="100%" height={260}>
            <BarChart data={revenusMensuels}>
              <CartesianGrid strokeDasharray="3 3" vertical={false} />
              <XAxis dataKey="mois" fontSize={11} tickLine={false} axisLine={false} />
              <YAxis fontSize={11} tickLine={false} axisLine={false} tickFormatter={(v) => `${(v / 1000000).toFixed(1)}M`} />
              <Tooltip content={<CustomBarTooltip />} />
              <Bar dataKey="revenu" fill="#3b82f6" radius={[6, 6, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
          <div className="mt-2 text-center text-xs text-muted-foreground">
            Total 6 mois: {formatPrice(total6Mois)}
          </div>
        </PanelCard>

        <PanelCard title="Medicaments populaires" className="lg:col-span-2">
          {topMedicaments.length === 0 ? (
            <div className="flex h-64 items-center justify-center text-muted-foreground">
              Aucune donnee disponible
            </div>
          ) : (
            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 items-center">
              <ResponsiveContainer width="100%" height={260}>
                <PieChart>
                  <Pie 
                    data={topMedicaments} 
                    dataKey="ventes" 
                    nameKey="nom" 
                    cx="50%" 
                    cy="50%" 
                    innerRadius={50} 
                    outerRadius={100} 
                    paddingAngle={2}
                    label={renderCustomLabel}
                    labelLine={false}
                  >
                    {topMedicaments.map((_, i) => <Cell key={i} fill={COLORS[i % COLORS.length]} />)}
                  </Pie>
                  <Tooltip content={<CustomPieTooltip />} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                </PieChart>
              </ResponsiveContainer>
              <div className="space-y-3">
                {topMedicaments.map((m, i) => (
                  <div key={m.nom} className="flex items-center gap-3">
                    <div className="h-3 w-3 rounded-sm" style={{ background: COLORS[i % COLORS.length] }} />
                    <div className="flex-1 text-sm font-medium">{m.nom}</div>
                    <div className="text-sm font-bold text-muted-foreground">{m.ventes} vendus</div>
                    <div className="text-sm font-semibold text-primary">{formatPrice(m.total)}</div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </PanelCard>
      </div>
    </AdminLayout>
  );
}
