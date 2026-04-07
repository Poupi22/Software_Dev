/** Country list with international dialing codes (ITU-T E.164) used in inquiry / order forms. */
export interface CountryInfo {
  name: string;
  code: string; // ISO 3166-1 alpha-2
  dial: string; // e.g. "+221"
  flag: string; // emoji flag
}

export const COUNTRIES: CountryInfo[] = [
  { name: "Sénégal",        code: "SN", dial: "+221", flag: "🇸🇳" },
  { name: "Côte d'Ivoire",  code: "CI", dial: "+225", flag: "🇨🇮" },
  { name: "Mali",           code: "ML", dial: "+223", flag: "🇲🇱" },
  { name: "Burkina Faso",   code: "BF", dial: "+226", flag: "🇧🇫" },
  { name: "Guinée",         code: "GN", dial: "+224", flag: "🇬🇳" },
  { name: "Bénin",          code: "BJ", dial: "+229", flag: "🇧🇯" },
  { name: "Togo",           code: "TG", dial: "+228", flag: "🇹🇬" },
  { name: "Cameroun",       code: "CM", dial: "+237", flag: "🇨🇲" },
  { name: "Gabon",          code: "GA", dial: "+241", flag: "🇬🇦" },
  { name: "Congo",          code: "CG", dial: "+242", flag: "🇨🇬" },
  { name: "Niger",          code: "NE", dial: "+227", flag: "🇳🇪" },
  { name: "Tchad",          code: "TD", dial: "+235", flag: "🇹🇩" },
  { name: "France",         code: "FR", dial: "+33",  flag: "🇫🇷" },
  { name: "Maroc",          code: "MA", dial: "+212", flag: "🇲🇦" },
  { name: "Algérie",        code: "DZ", dial: "+213", flag: "🇩🇿" },
  { name: "Tunisie",        code: "TN", dial: "+216", flag: "🇹🇳" },
  { name: "Mauritanie",     code: "MR", dial: "+222", flag: "🇲🇷" },
];

export const findCountry = (name: string) => COUNTRIES.find((c) => c.name === name);
