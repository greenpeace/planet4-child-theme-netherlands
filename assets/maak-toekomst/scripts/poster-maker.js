import BackgroundImage from '../images/background.png';
import madeDillanWoff from '../fonts/made-dillan.woff';
import madeDillanWoff2 from '../fonts/made-dillan.woff2';

const canvas = document.getElementById('canvas_poster');
const ctx = canvas.getContext('2d');
const canvasWidth = 2480; // Width of the image
const canvasHeight = 3508; // Height of the image

canvas.width = canvasWidth;
canvas.height = canvasHeight;

const lineHeight = 390;
const fontSize = '390px';

ctx.fillStyle = '#00103d';
ctx.font = fontSize + ' ' + 'Dillan';
ctx.textAlign = 'center';


const background = new Image();
background.src = BackgroundImage;

const canvasInput = document.getElementById('canvas_input');
let text = canvasInput.value;

// Update canvas whenever text on input changes.
canvasInput.addEventListener('input', function (e) {
  text = e.target.value.toLowerCase();
  updateCanvas();
});


function updateCanvas() {
  ctx.drawImage(background, 0, 0);
  let lines = getLines(ctx, text, canvas.width * 0.9); // * 0.9 for a small margin.
  let numberOfLines = lines.length;
  lines.forEach((line, i) => {
    // Print lines on Y axis depending on total number of lines plus the line height for each line.
    printLine(line, 1400 - (lineHeight / 2 * numberOfLines) + i * lineHeight);
  });
}

// Returns an array with lines of text (for wrapping).
function getLines(ctx, text, maxWidth) {
  const words = text.split(' ');
  let lines = [];
  let currentLine = words[0];

  for (let i = 1; i < words.length; i++) {
    const word = words[i];
    const width = ctx.measureText(currentLine + ' ' + word).width;
    if (width < maxWidth) {
      currentLine += ' ' + word;
    } else {
      lines.push(currentLine);
      currentLine = word;
    }
  }
  lines.push(currentLine);
  return lines;
}


function printLine(line, y) {
  ctx.fillText(line, canvas.width / 2, y);
}

// This function can be called from the button in HTML.
function downloadCanvasAsImage(){
  let downloadLink = document.createElement('a');
  downloadLink.setAttribute('download', 'poster.png');
  let canvas = document.getElementById('canvas_poster');
  let dataURL = canvas.toDataURL('image/png');
  downloadLink.setAttribute('href',dataURL);
  downloadLink.click();
}

// Update the canvas when the background image had loaded.
background.onload = function () {
  updateCanvas();
};

let dillanFont = new FontFace('Dillan', `url(${madeDillanWoff})`);
dillanFont.load().then(
  font => {
    // Font is loaded, make it available to the DOM and update the canvas.
    document.fonts.add(font);
    updateCanvas();
  },
  error => {
    console.log(error.toString());
  }
);
