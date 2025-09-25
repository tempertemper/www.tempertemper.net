function convertToSmartQuotes(text) {
  if (!text) return "";

  // Take the original text
  let result = text;

  let inDoubleQuote = false;
  // Replace double quotes with smart quotes
  // This will toggle between opening and closing quotes based on whether we are currently inside a double quote
  result = result.replace(/"/g, () => {
    inDoubleQuote = !inDoubleQuote;
    return inDoubleQuote ? "\u201C" : "\u201D";
  });

  // Handle contractions and possessives
  const contractions = /(\w)'(\w)/g;
  const possessives = /(\w)'s(\s|$)/g;

  // Replace contractions and possessives with smart quotes
  result = result.replace(contractions, "$1\u2019$2");
  result = result.replace(possessives, "$1\u2019s$2");

  // Replace single quotes with smart quotes
  let inSingleQuote = false;
  // This will toggle between opening and closing quotes based on whether we are currently inside a single quote
  result = result.replace(/'/g, () => {
    inSingleQuote = !inSingleQuote;
    return inSingleQuote ? "\u2018" : "\u2019";
  });

  // Return the updated text
  return result;
}

module.exports = convertToSmartQuotes;
