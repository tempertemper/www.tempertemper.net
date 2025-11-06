/*
An ISO format date formatter filter for Nunjucks
*/
export default function isoDate(date) {
  const d = new Date(date);
  return d.toISOString().slice(0, 10);
}
