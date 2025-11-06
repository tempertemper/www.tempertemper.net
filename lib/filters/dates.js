/*
A date formatter filter for Nunjucks
*/
export default function dates(date, part) {
  const d = new Date(date);
  if (part === 'year') {
    return d.getUTCFullYear();
  }

  const month = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ];

  const ordinal = {
    1: 'st',
    2: 'nd',
    3: 'rd',
    21: 'st',
    22: 'nd',
    23: 'rd',
    31: 'st',
  };

  const day = d.getDate();
  return `${day}${ordinal[day] || 'th'} ${month[d.getMonth()]} ${d.getUTCFullYear()}`;
}
