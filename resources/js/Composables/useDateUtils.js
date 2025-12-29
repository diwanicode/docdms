// resources/js/Composables/useDateUtils.js
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useDateUtils() {
  const page = usePage();
  const translations = computed(() => page.props.translations || {});
  const months = computed(() => translations.value.general?.months || []);
  const weekdays = computed(() => translations.value.general?.days || []);

  // TODO Emina write test for this function
  const formatDate = (date, format = 'MMMM D') => {
    if (!(date instanceof Date)) date = new Date(date); // Accept string too
    format = String(format);

    const year = date.getFullYear();
    const monthIndex = date.getMonth(); // 0-based
    const day = date.getDate();
    const weekdayIndex = date.getDay() === 0 ? 7 : date.getDay();; // 1 = Mon 
    const month = months.value[monthIndex];
    const weekday = weekdays.value.find(q => q.id === weekdayIndex);

    const pad = (num) => String(num).padStart(2, '0');  
    const replacements = {
      YYYY: year,    //2025
      MM: pad(monthIndex + 1), //
      M: monthIndex + 1,    //7  
      MMMM: month?.long, //July
      MMM: month?.short, //Jul
      DD: pad(day), //24
      D: day,     //24
      dddd: weekday?.long || weekday,   // Monday
      ddd: weekday?.short || weekday?.substring(0, 3), // Mon
    }; 
    
    return format.replace(/YYYY|MMMM|MMM|MM|M|DD|D|dddd|ddd/g, match => replacements[match] || match);
  };
  /**
   * Formats a date range into a string.
   * 
   * @param {Date} startDate - Start date in format "YYYY-MM-DD"
   * @param {Date} endDate - End date in format "YYYY-MM-DD"
   * @param {boolean} [useShort=false] - Unused in this ISO date version
   * @returns {string} Date range string "YYYY-MM-DD - YYYY-MM-DD"
   */
  const formatDateRange = (startDate, endDate, useShort = false) => {
    const sameMonth = startDate.getMonth() === endDate.getMonth();
    const sameYear = startDate.getFullYear() === endDate.getFullYear();

    const start = formatDate(startDate, 'D MMM');
    const end = formatDate(endDate, 'D MMM');
    const year = endDate.getFullYear();
    if (sameMonth && sameYear) {
      return `${start} - ${end}, ${year}`;
    } else if (sameYear) {
      return `${start} - ${end}, ${year}`;
    } else {
      return `${start}, ${startDate.getFullYear()} - ${end}, ${year}`;
    }
  };
  /**
   * Get all dates (Monday–Sunday) for the week of a given date.
   *
   * @param {Date} date - A JavaScript Date object representing any day within the desired week.
   * @returns {Date[]} weekDates - An array of 7 Date objects, starting from Monday and ending with Sunday,
   *                               each representing one day of that week.
   *
   * Example:
   *   getCurrentWeekDates(new Date("2025-08-30"))
   *   // -> [Mon Aug 25 2025, Tue Aug 26 2025, ..., Sun Aug 31 2025]
   */
  function getCurrentWeekDates(date) {  
    const currentDay = date.getDay()
    const monday = new Date(date)
    monday.setDate(date.getDate() - (currentDay === 0 ? 6 : currentDay - 1))

    const weekDates = []
    for (let i = 0; i < 7; i++) {
      const date = new Date(monday)
      date.setDate(monday.getDate() + i)
      weekDates.push(date)
    }
    return weekDates
  }
  /**
 * Get the number of days between two dates (inclusive).
 *
 * This function calculates the difference in days between a start date
 * and an end date. It resets the time part (hours, minutes, seconds, ms)
 * to avoid off-by-one errors caused by time differences.
 *
 * Example:
 *   getDaysBetween("2025-09-01", "2025-09-30") // returns 30
 *
 * @param {string|Date} startDate - The start date (string in YYYY-MM-DD or Date object)
 * @param {string|Date} endDate - The end date (string in YYYY-MM-DD or Date object)
 * @returns {number} The number of days in the range (inclusive of both start and end date)
 */
  function getDaysBetween(startDate, endDate) {
    const start = new Date(startDate)
    const end = new Date(endDate)

    // Reset time to midnight for accurate calculation
    start.setHours(0, 0, 0, 0)
    end.setHours(0, 0, 0, 0)

    const diffTime = end.getTime() - start.getTime()
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1

    return diffDays
  }
  
  const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

  const formatUtcToLocalTime = (utcString, locale = 'en-GB', options = {}) => {
    const date = new Date(utcString);
    return date.toLocaleTimeString(locale, {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
      timeZone: userTimeZone,
      ...options
    });
  };
 function getUserTimeZone() { 
  // Get long name
  const partsLong = Intl.DateTimeFormat('en-US', {
      timeZone: userTimeZone,
      timeZoneName: 'long',
    }).formatToParts(new Date());

    const timeZoneNameLong = partsLong.find(p => p.type === 'timeZoneName')?.value;

    // Get short name
    const partsShort = Intl.DateTimeFormat('en-US', {
      timeZone: userTimeZone,
      timeZoneName: 'short',
    }).formatToParts(new Date());

    const timeZoneNameShort = partsShort.find(p => p.type === 'timeZoneName')?.value;

    return {
      id: userTimeZone,
      label: [timeZoneNameLong, timeZoneNameShort].filter(Boolean).join(' / '),
    };
  }
  const getDateRange = (periodType = 'weekly',selectedDate, useShort = false) => {
    let start, end;

    if (periodType === 'weekly') {
      start = new Date(selectedDate);
      start.setDate(selectedDate.getDate() - ((selectedDate.getDay() + 6) % 7)); // Monday
      end = new Date(start);
      end.setDate(start.getDate() + 6);
    } else if (periodType === 'monthly') {
      start = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
      end = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0); // Last day of current month
    } else {
      throw new Error(`Unsupported period type: ${periodType}`);
    }
     
    return formatDateRange(start, end);
  };
  const getDaysInMonth = (selectedDate,  format = 'MMMM D') => {
    const year = selectedDate.value.getFullYear();
    const month = selectedDate.value.getMonth(); 
    const days = [];
    const totalDays = new Date(year, month + 1, 0).getDate(); 
    for (let i = 1; i <= totalDays; i++) {
      days.push({
        dayNum: i,
        date: formatDate(new Date(year, month, i),format),
      });
    }
    return days;
  };

  const addMonths = (date, amount) => {
    return new Date(date.getFullYear(), date.getMonth() + amount, 1);
  };

  const subMonths = (date, amount) => {
    return new Date(date.getFullYear(), date.getMonth() - amount, 1);
  };

  const getWeekdayPadding = (date) => {
    const dayIndex = date.getDay(); // 0 = Sunday
    return dayIndex === 0 ? 6 : dayIndex - 1; // Convert to Monday=0, Sunday=6
  };
  const getNameInitials = (name) => {
    if (!name || typeof name !== 'string' || name.trim() === '') {
      return ''
    }

    const words = name.trim().split(/\s+/)

    if (words.length === 1) {
      return words[0].charAt(0).toUpperCase()
    }

    return (
      words[0].charAt(0) + words[1].charAt(0)
    ).toUpperCase()
  }

  return {
    formatDate,
    formatDateRange,
    formatUtcToLocalTime,
    getDateRange,
    getDaysInMonth,
    addMonths,
    subMonths,
    getWeekdayPadding,
    getCurrentWeekDates,
    getDaysBetween,
    getUserTimeZone,
    getNameInitials
  };
}
