let focus = document.getElementsByClassName('ticketcontainer')[0];

let login = document.getElementsByClassName('loginButton')[0];
let user = document.getElementsByClassName('userBoxContainer')[0];
let tickets = document.getElementsByClassName('rightcollumnrepertuarelementbuyticket');

let logintouser = document.getElementsByClassName('loginBoxUser')[0];
let usertologin = document.getElementsByClassName('loginBoxUser')[1];

let returnbutton = document.getElementsByClassName('ReturnButton');

let loginbox = document.getElementsByClassName('loginBox')[0];
let ticketbox = document.getElementsByClassName('ticketBox')[0];


var table = document.getElementsByClassName("locationlist")[0];
var cells = table.getElementsByTagName("td");


let value = document.getElementById('valueBox');
//Zdarzenie aktywujace pole do logowania
login.addEventListener('click', () => {
  loginbox.classList.add('activefield');
});


//Zdarzenie zmieniajace pole do logowania na pole do rejestracji
logintouser.addEventListener('click', () => {
  user.style.margin = '0px';
});
usertologin.addEventListener('click', () => {
  user.style.margin = '1000px';
});


//Zdarzenie aktywujace pole z biletami
Array.from(tickets).forEach(ticketElement => {
  ticketElement.addEventListener('click', () => {
    ticketbox.classList.add('activefield');
    var number = ticketElement.outerHTML;
    var cookie = number.replace(/\D/g, '');
    document.cookie = "idfocus = " + cookie;

    var blocked = value.innerHTML;
    blocked = blocked.split('.').map(Number);
    blocked = blocked.filter(function (val) {
      return val !== 0;
    });
    var ticketforthisrepertuar = [];
    for (var i = 0; i < cells.length; i++) {
      ticketforthisrepertuar[i] = i + 1;
    }

    for (var i = 0; i < blocked.length; i++) {
      ticketforthisrepertuar = ticketforthisrepertuar.filter(function (val) {
        return val !== blocked[i];
      });
    }

    NotBlocked(ticketforthisrepertuar, 0);
  });
});
//Funkcja sprawdzajaca jakie bilety sa do kupienia
function NotBlocked(numberarray, index) {
  var numbertofind = numberarray[index];
  var cellsLength = cells.length;

  for (var ticket = 0; ticket < cellsLength; ticket++) {
    var number = parseInt(cells[ticket].innerText);
    if (number == numbertofind) {
      cells[number - 1].classList.add('buyTicket');
      index = index + 1;
      var numbertofind = numberarray[index];
    }
  }

  go();
}


//Funkcja przechodzaca do pola zakupu
function go() {
  let clickticket = document.getElementsByClassName('buyTicket');
  Array.from(clickticket).forEach(ticketElement => {
    ticketElement.addEventListener('click', () => {
      focus.style.left = '0px';
      var ticket = ticketElement.innerHTML;
      var ticketfocus = document.getElementsByClassName("loginticket")[0];
      ticketfocus.textContent = "Wybrales miejsce numer " + ticket;
      document.cookie = "ticketfocus = " + ticket;
    });
  });
}





//Zdarzenie zamykajace wszystkie inne aktywnosci
Array.from(returnbutton).forEach(returnElement => {
  returnElement.addEventListener('click', () => {
    //deleteAllCookies();
    console.log("usunieto cookie");
    if (loginbox.classList.contains('activefield')) {
      loginbox.classList.remove('activefield');
    }
    if (ticketbox.classList.contains('activefield')) {
      ticketbox.classList.remove('activefield');
      focus.style.left = '1000%';
      //var urlParams = new URLSearchParams(window.location.search);
      //var focusdate = urlParams.get('focusdate');
      //var redirectURL = "index.php?focusdate=" + encodeURIComponent(focusdate);
      //window.location.href = redirectURL;
      //var redirectURL = "index.php";
      //window.location.href = redirectURL;
    }
  });
});

function deleteAllCookies() {
  const cookies = document.cookie.split(";");

  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i];
    const eqPos = cookie.indexOf("=");
    const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
  }
}