/*
Converts straight quotes to smart quotes
*/
export default function convertToSmartQuotes(text) {
  if (!text) return '';

  let result = text;

  // Double quotes
  let inDoubleQuote = false;
  result = result.replace(/"/g, () => {
    inDoubleQuote = !inDoubleQuote;
    return inDoubleQuote ? '\u201C' : '\u201D';
  });

  // Contractions and possessives
  const contractions = /(\w)'(\w)/g;
  const possessives = /(\w)'s(\s|$)/g;
  result = result.replace(contractions, '$1\u2019$2');
  result = result.replace(possessives, '$1\u2019s$2');

  // Single quotes
  let inSingleQuote = false;
  result = result.replace(/'/g, () => {
    inSingleQuote = !inSingleQuote;
    return inSingleQuote ? '\u2018' : '\u2019';
  });

  return result;
}
