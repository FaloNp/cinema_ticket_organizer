// Funkcja do tworzenia kalendarza
var date = new Date();

var urlParams = new URLSearchParams(window.location.search);
var focusdate = urlParams.get('focusdate');
if (focusdate) {
  var dateArray = focusdate.split('-');
  var currentyear = dateArray[0];
  var currentmonth = dateArray[1] - 1;
  var currentday = dateArray[2] - 1 + 1;//usuwa 0 przed cyfra
}
else {
  var currentyear = date.getFullYear();
  var currentmonth = date.getMonth();
  var currentday = date.getDate();
}

//console.log(currentday + "." + (currentmonth + 1) + "." + currentyear);

var yearchange = currentyear;
var monthchange = currentmonth;
var focusday = 0;

function calendar(month, year) {
  var dniWMiesiacu = new Date(year, month + 1, 0).getDate();
  var poczatekMiesiaca = new Date(year, month, 1).getDay();


  var table = document.getElementsByClassName("mycalendar")[0];
  var row = table.getElementsByTagName("tr");

  var today = document.getElementById('today');
  today.innerHTML = (month + 1) + "." + year;

  var day = 1;
  var startDay = poczatekMiesiaca === 0 ? 6 : poczatekMiesiaca - 1; // Dostosowanie indeksowania dni

  for (var week = 0; week < row.length; week++) {
    var cells = row[week].getElementsByTagName("td");
    for (var dayinweek = 0; dayinweek < cells.length; dayinweek++) {
      if (week === 0 && dayinweek < startDay || day > dniWMiesiacu) {
        cells[dayinweek].textContent = '';
      }
      else {
        cells[dayinweek].textContent = day < 10 ? '0' + day : day;
        if (day === currentday && month === currentmonth && year === currentyear) {
          cells[dayinweek].style.color = 'white';
        } else {
          cells[dayinweek].style.color = 'black';
        }


        if (day == date.getDate() && month == date.getMonth() && year == date.getFullYear()) {
          cells[dayinweek].style.color = '#997501';
        }
        day++;
      }
    }
  }
}

calendar(currentmonth, currentyear);

var Left = document.getElementsByClassName('calendarButton')[0];
var Right = document.getElementsByClassName('calendarButton')[1];

Left.addEventListener('click', () => {
  monthchange = monthchange - 1;
  focusday = 0;
  if (monthchange < 0) {
    monthchange = 11;
    yearchange = yearchange - 1;
  }
  calendar(monthchange, yearchange);
});

Right.addEventListener('click', () => {
  monthchange = monthchange + 1;
  focusday = 0;
  if (monthchange > 11) {
    monthchange = 0;
    yearchange = yearchange + 1;
  }
  calendar(monthchange, yearchange);
});


// Funkcja do obsługi zdarzenia kliknięcia w pole tabeli
function handleCellClick(event) {
  var date = event.target.textContent;
  focusday = date;
  calendar(monthchange, yearchange);
  var focusdate = yearchange + "-" + (monthchange + 1) + "-" + focusday
  if (date) {
    var redirectURL = "index.php?focusdate=" + encodeURIComponent(focusdate);
    window.location.href = redirectURL;
  }
}

// Pobierz wszystkie pola tabeli i dodaj nasłuchiwanie zdarzeń kliknięcia
var cells = document.querySelectorAll('.mycalendar td');
cells.forEach(function (cell) {
  cell.addEventListener('click', handleCellClick);
});