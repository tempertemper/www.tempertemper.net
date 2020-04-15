/*
A ISO format date formatter filter for Nunjucks
*/
module.exports = function(date) {
  var d = new Date(date);
  return d.toISOString().slice(0, 10);
}
