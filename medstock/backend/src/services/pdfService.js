const PDFDocument = require('pdfkit');
const { query } = require('../config/database');

async function genererFacturePDF(venteId, res) {
  try {
    const venteResult = await query(`
      SELECT v.*, u.nom as vendeur_nom
      FROM ventes v
      LEFT JOIN utilisateurs u ON v.utilisateur_id = u.id
      WHERE v.id = $1
    `, [venteId]);
    
    if (venteResult.rows.length === 0) {
      throw new Error('Vente non trouvée');
    }
    
    const vente = venteResult.rows[0];
    
    const lignesResult = await query(`
      SELECT vl.*, m.nom as medicament_nom
      FROM vente_lignes vl
      LEFT JOIN medicaments m ON vl.medicament_id = m.id
      WHERE vl.vente_id = $1
    `, [venteId]);
    
    const lignes = lignesResult.rows;
    
    const doc = new PDFDocument({ margin: 50, size: 'A4' });
    
    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `inline; filename=facture_${vente.numero}.pdf`);
    
    doc.pipe(res);
    
    // En-tête
    doc.fontSize(20).font('Helvetica-Bold').text('PHARMACARE', { align: 'center' });
    doc.fontSize(10).font('Helvetica').text('Système de Gestion de Stock', { align: 'center' });
    doc.moveDown();
    doc.fontSize(8).text('Tél: +237 690 981 048', { align: 'center' });
    doc.text('Email: contact@pharmacare.com', { align: 'center' });
    doc.moveDown();
    
    doc.lineWidth(1).moveTo(50, doc.y).lineTo(550, doc.y).stroke();
    doc.moveDown();
    
    // Infos facture
    doc.fontSize(12).font('Helvetica-Bold').text('FACTURE', { align: 'center' });
    doc.moveDown();
    
    doc.fontSize(10).font('Helvetica');
    doc.text(`N° Facture: ${vente.numero}`, 50, doc.y);
    doc.text(`Date: ${new Date(vente.created_at).toLocaleDateString('fr-FR')}`, 400, doc.y - 15);
    doc.text(`Heure: ${new Date(vente.created_at).toLocaleTimeString('fr-FR')}`, 400, doc.y);
    doc.moveDown();
    
    // Infos client
    doc.fontSize(10).font('Helvetica-Bold').text('Informations client:', 50, doc.y);
    doc.fontSize(10).font('Helvetica');
    doc.text(`Nom: ${vente.client_nom || 'Non spécifié'}`, 50, doc.y + 15);
    doc.text(`Téléphone: ${vente.client_telephone || 'Non spécifié'}`, 50, doc.y + 30);
    if (vente.client_whatsapp) {
      doc.text(`WhatsApp: ${vente.client_whatsapp}`, 50, doc.y + 45);
    }
    doc.moveDown(3);
    
    // Tableau des produits
    let yPosition = doc.y;
    doc.fontSize(10).font('Helvetica-Bold');
    doc.text('Produit', 50, yPosition);
    doc.text('Qté', 300, yPosition);
    doc.text('Prix unit.', 380, yPosition);
    doc.text('Total', 470, yPosition);
    
    yPosition += 5;
    doc.lineWidth(0.5).moveTo(50, yPosition).lineTo(550, yPosition).stroke();
    yPosition += 10;
    
    doc.fontSize(9).font('Helvetica');
    for (const ligne of lignes) {
      doc.text(ligne.nom_snapshot.substring(0, 40), 50, yPosition);
      doc.text(ligne.quantite.toString(), 300, yPosition);
      doc.text(`${ligne.prix_unitaire.toLocaleString()} FCFA`, 380, yPosition);
      doc.text(`${ligne.total_ligne.toLocaleString()} FCFA`, 470, yPosition);
      yPosition += 20;
      
      if (yPosition > 700) {
        doc.addPage();
        yPosition = 50;
      }
    }
    
    doc.lineWidth(0.5).moveTo(50, yPosition).lineTo(550, yPosition).stroke();
    yPosition += 10;
    
    // Totaux
    doc.fontSize(10).font('Helvetica-Bold');
    doc.text('Sous-total:', 400, yPosition);
    doc.text(`${vente.sous_total.toLocaleString()} FCFA`, 470, yPosition);
    yPosition += 20;
    
    if (vente.remise > 0) {
      doc.text('Remise:', 400, yPosition);
      doc.text(`-${vente.remise.toLocaleString()} FCFA`, 470, yPosition);
      yPosition += 20;
    }
    
    if (vente.tva > 0) {
      doc.text('TVA (19%):', 400, yPosition);
      doc.text(`${vente.tva.toLocaleString()} FCFA`, 470, yPosition);
      yPosition += 20;
    }
    
    doc.fontSize(12).font('Helvetica-Bold');
    doc.text('TOTAL:', 400, yPosition);
    doc.text(`${vente.total.toLocaleString()} FCFA`, 470, yPosition);
    yPosition += 30;
    
    // Pied de page
    doc.fontSize(8).font('Helvetica');
    doc.text('Mode de paiement: ' + vente.mode_paiement, 50, yPosition);
    doc.text('Statut: ' + vente.statut, 400, yPosition);
    yPosition += 20;
    doc.text('Merci de votre confiance !', { align: 'center' });
    
    doc.end();
  } catch (error) {
    console.error('PDF generation error:', error);
    throw error;
  }
}

module.exports = { genererFacturePDF };