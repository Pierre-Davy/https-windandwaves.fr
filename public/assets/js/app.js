// Fichier Javascript gérant le coeur de l'application météo

//-------------GET Data time -------------------------
let date = new Date();

// --------------------WIDTH AND HEIGHT SCREEN-----------------------------
// console.log(window.innerWidth);
// console.log(window.innerHeight);

//------------Initialisation du canvas------------

let canvasWidthAndHeight = 1000;
let mapZoomLevel = 15;
if (window.innerWidth < 600) {
  canvasWidthAndHeight = 500;
  mapZoomLevel = 14;
}

canvasContainer.innerHTML = `<canvas
id="canvas"
width="${canvasWidthAndHeight}"
height="${canvasWidthAndHeight}"
style="width: 100%; height: auto"
></canvas><div id="map" style="width: 100%"></div>`;

//----------------------------------------------------------------

const choiceLocations = document.getElementsByClassName("choiceLocation");
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

let latitude = 48.37;
let longitude = -4.765;
let meteoData = [];
let meteoDataMarine = [];
let hourSelect = date.getHours();
let locationId = 0;



//------------Initialisation de la carte------------

displayMap(latitude, longitude);

//Click event parametres

for (let i = 0; i < choiceLocations.length; i++) {
  choiceLocations[i].addEventListener("click", () => {
    for (let i = 0; i < choiceLocations.length; i++) {
      choiceLocations[i].classList.remove("selected")
    }
    choiceLocations[i].classList.add("selected")
    console.log(choiceLocations[i])
    function maLocation(lieu) {
      return lieu.name === choiceLocations[i].id;
    }
    locationId = i;
    let result = locationData.find(maLocation);
    latitude = result.LocateX;
    longitude = result.LocateY;
    recupData();
    displayMap(latitude, longitude, "STREETS");
    return latitude, longitude, locationId;
  });
}

nextHour.addEventListener("click", () => {
  if (hourSelect < 96) {
    hourSelect++;
    meteoDataDisplay();
  }
});
previousHour.addEventListener("click", () => {
  if (hourSelect > 0) {
    hourSelect--;
    meteoDataDisplay();
  }
});

satelliteView.addEventListener("click", () => {
  displayMap(latitude, longitude, "SATELLITE");
});
streetView.addEventListener("click", () => {
  displayMap(latitude, longitude, "STREETS");
});

// ------------function fetch location ---------------------------------
async function recupDataLocation() {
  await fetch("public/assets/location/Location.json")
    .then((response) => response.json())
    .then((data) => (locationData = data));
  return locationData;
}
recupDataLocation();

// ---------------------function fetch meteo data---------------------------
async function recupData() {
  await fetch(
    "https://api.open-meteo.com/v1/meteofrance?latitude=" +
      latitude +
      "&longitude=" +
      longitude +
      "&hourly=temperature_2m,weather_code,windspeed_10m,windgusts_10m,winddirection_10m&windspeed_unit=kn"
  )
    .then((response) => response.json())
    .then((data) => (meteoData = data));

  await fetch(
    "https://marine-api.open-meteo.com/v1/marine?latitude=" +
      latitude +
      "&longitude=" +
      longitude +
      "&hourly=wave_height,wave_direction,wave_period&length_unit=metric"
  )
    .then((response) => response.json())
    .then((data) => (meteoDataMarine = data));

  meteoDataDisplay();
}

// -------------------------function display map-----------------------------------

function displayMap(latitude, longitude, modelViewMap) {
  maptilersdk.config.apiKey = "OvBRSxpI8Y6ygIO2ph5A";
  const map = new maptilersdk.Map({
    container: "map", // container's id or the HTML element to render the map
    style: maptilersdk.MapStyle[modelViewMap],
    center: [longitude, latitude], // starting position [lng, lat]
    zoom: mapZoomLevel, // starting zoom
  });
}

// --------------------------function display meteo data---------------------------
function meteoDataDisplay() {
  dateContainer.innerHTML = `<h3>Date</h3><p>${meteoData.hourly.time[
    hourSelect
  ].slice(0, 10)}</p>`;
  hourContainer.innerHTML = `<h3>Heure</h3><p>${meteoData.hourly.time[
    hourSelect
  ].slice(11, 16)}</p>`;
  temperature.innerHTML = `<h3>Temperature</h3><p>${meteoData.hourly.temperature_2m[hourSelect]}  ${meteoData.hourly_units.temperature_2m}</p>`;

  //console.log(`${meteoData.hourly.weather_code[hourSelect]}`)
  // Enumération des cas de météo
  switch (`${meteoData.hourly.weather_code[hourSelect]}`) {
    case '0':
      situation.innerHTML = `<h3>Situation</h3><p>Ciel dégagé</p>`;
      break;
    case '1':
      situation.innerHTML = `<h3>Situation</h3><p>Clair</p>`;
      break;
    case '2':
      situation.innerHTML = `<h3>Situation</h3><p>Partiellement nuageux</p>`;
      break;
    case '3':
      situation.innerHTML = `<h3>Situation</h3><p>Couvert</p>`;
      break;
    case '45':
      situation.innerHTML = `<h3>Situation</h3><p>Brouillard</p>`;
      break;
    case '48':
      situation.innerHTML = `<h3>Situation</h3><p>Brouillard givrant</p>`;
      break;
    case '51':
      situation.innerHTML = `<h3>Situation</h3><p>Bruine légère</p>`;
      break;
    case '53':
      situation.innerHTML = `<h3>Situation</h3><p>Bruine modérée</p>`;
      break;
    case '55':
      situation.innerHTML = `<h3>Situation</h3><p>Bruine dense</p>`;
      break;
    case '56':
    case '57':
      situation.innerHTML = `<h3>Situation</h3><p>Bruine verglaçante</p>`;
      break;
    case '61':
      situation.innerHTML = `<h3>Situation</h3><p>Pluie légère</p>`;
      break;
    case '63':
      situation.innerHTML = `<h3>Situation</h3><p>Pluie modérée</p>`;
      break;
    case '65':
      situation.innerHTML = `<h3>Situation</h3><p>Pluie forte</p>`;
      break;
    case '66':
    case '67':
      situation.innerHTML = `<h3>Situation</h3><p>Pluie verglaçante</p>`;
      break;
    case '71':
      situation.innerHTML = `<h3>Situation</h3><p>Neige légère</p>`;
      break;
    case '73':
      situation.innerHTML = `<h3>Situation</h3><p>Neige modérée</p>`;
      break;
    case '75':
      situation.innerHTML = `<h3>Situation</h3><p>Neige forte</p>`;
      break;
    case '77':
      situation.innerHTML = `<h3>Situation</h3><p>Neige en grains</p>`;
      break;
    case '80':
      situation.innerHTML = `<h3>Situation</h3><p>Averses légères</p>`;
      break;
    case '81':
      situation.innerHTML = `<h3>Situation</h3><p>Averses modérées</p>`;
      break;
    case '82':
      situation.innerHTML = `<h3>Situation</h3><p>Averses violentes</p>`;
      break;
    case '85':
    case '86':
      situation.innerHTML = `<h3>Situation</h3><p>Tempête de neige</p>`;
      break;
    case '95':
      situation.innerHTML = `<h3>Situation</h3><p>Orage</p>`;
      break;
    case '96':
    case '99':
      situation.innerHTML = `<h3>Situation</h3><p>Orage avec grêle</p>`;
      break;

    default:
      situation.innerHTML = `<h3>Situation</h3><p>N/R</p>`;
  }

  windspeed.innerHTML = `<h3>Vitesse du vent</h3><p>${meteoData.hourly.windspeed_10m[hourSelect]} ${meteoData.hourly_units.windspeed_10m}</p>`;
  windgust.innerHTML = `<h3>Rafale</h3><p>${meteoData.hourly.windgusts_10m[hourSelect]} ${meteoData.hourly_units.windgusts_10m}</p>`;
  winddirection.innerHTML = `<h3>Direction du vent</h3><p>${meteoData.hourly.winddirection_10m[hourSelect]} ${meteoData.hourly_units.winddirection_10m}</p>`;
  waveHeight.innerHTML = `<h3>Hauteur de la houle</h3><p>${meteoDataMarine.hourly.wave_height[hourSelect]} ${meteoDataMarine.hourly_units.wave_height}</p>`;
  waveDirection.innerHTML = `<h3>Direction de la houle</h3><p>${meteoDataMarine.hourly.wave_direction[hourSelect]} ${meteoDataMarine.hourly_units.wave_direction}</p>`;
  wavePeriod.innerHTML = `<h3>Periode de houle</h3><p>${meteoDataMarine.hourly.wave_period[hourSelect]} ${meteoDataMarine.hourly_units.wave_period}</p>`;

  let angle = meteoData.hourly.winddirection_10m[hourSelect]; // Degree
  // let angle =300; // ----> for testing
  //----------------------------------display Alert wind Direction--------------------------------------

  alertWindDirection.innerHTML = "";
  winddirection.style.backgroundColor = "";

  /*
  console.log("angle du vent : " + angle);
  console.log(
    "angle minimum : " +
      ((locationData[locationId].orientationBeach + 180) % 360)
  );
  console.log("plage : " + locationData[locationId].name);
  console.log("angle maximum : " + locationData[locationId].orientationBeach);
  console.log(
    "angle plus petit que mini : " +
      (angle < (locationData[locationId].orientationBeach + 180) % 360)
  );
  console.log(
    "angle plus grand que max : " +
      (angle > locationData[locationId].orientationBeach)
  );
*/
  if (
    angle < (locationData[locationId].orientationBeach + 180) % 360 &&
    angle > locationData[locationId].orientationBeach
  ) {
    winddirection.style.backgroundColor = "red";
    alertWindDirection.innerHTML = `<div class ="alertWindDirectionBox"><h2>ATTENTION</h2><p>Pensez à verifier l'orientation du vent</div></p>`;
  }

  // ALERT WIND DIRECTION PLOUGONVELIN

  if (
      angle < locationData[locationId].orientationBeach[0] ||
      angle > locationData[locationId].orientationBeach[1]
  ) {
    winddirection.style.backgroundColor = "red";
    alertWindDirection.innerHTML = `<div class ="alertWindDirectionBox"><h2>ATTENTION</h2><p>Pensez à verifier l'orientation du vent</div></p>`;
  }

  // -------------------------- display canvas wind direction----------------------------------------------------------

  let axeX;
  let axeY;
  let axeXPrime;
  let axeYPrime;
  let axeXArrow;
  let axeYArrow;
  let length = 75;
  let lengthArrow = 10;

  ctx.clearRect(0, 0, 1000, 1000);

  // ctx.drawImage(imgFond, 0, 0, 1000, 1000);

  for (x = 0; x <= 1000; x += 50) {
    for (y = 0; y <= 1000; y += 50) {
      axeX = x + Math.random() * 50;
      axeY = y + Math.random() * 50;
      // axeX = x;
      // axeY = y;

      axeXPrime = axeX + Math.cos((Math.PI * (angle + -90)) / 180) * length;
      axeYPrime = axeY + Math.sin((Math.PI * (angle + -90)) / 180) * length;
      axeXArrow =
        axeX + Math.cos((Math.PI * (angle + -70)) / 180) * lengthArrow;
      axeYArrow =
        axeY + Math.sin((Math.PI * (angle + -70)) / 180) * lengthArrow;

      // ctx.fillStyle = "rgb(0,0,0)";
      // ctx.fillRect(axeX - 1.5, axeY - 1.5, 3, 3);

      ctx.beginPath();
      ctx.moveTo(axeX, axeY);
      ctx.lineTo(axeXPrime, axeYPrime);
      ctx.moveTo(axeX, axeY);
      ctx.lineTo(axeXArrow, axeYArrow);

      ctx.stroke();
    }
  }
}

window.addEventListener("load", recupData());
