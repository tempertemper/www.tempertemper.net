/*
A date formatter filter for Nunjucks
*/
export default function dates(date, part) {
  const d = new Date(date);

  const months = [
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

  const ordinals = {
    1: 'st',
    2: 'nd',
    3: 'rd',
    21: 'st',
    22: 'nd',
    23: 'rd',
    31: 'st',
  };

  const day = d.getUTCDate();
  const dayWithOrdinal = `${day}${ordinals[day] || 'th'}`;
  const month = months[d.getUTCMonth()];
  const year = d.getUTCFullYear();

  if (part === 'year') {
    return year;
  }

  if (part === 'dayMonth') {
    return `${dayWithOrdinal} ${month}`;
  }

  return `${dayWithOrdinal} ${month} ${year}`;
}
