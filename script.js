const bpmCircle = document.querySelector('.ring-progress');
const bpmText = document.getElementById('bpm');
const radius = 70;
const circumference = 2 * Math.PI * radius;

bpmCircle.style.strokeDasharray = circumference;

function setBPM(bpm) {
  bpmText.textContent = bpm;
  
  const percent = Math.min(bpm / 200, 1); 
  const offset = circumference * (1 - percent);
  bpmCircle.style.strokeDashoffset = offset;
}


let currentBPM = 60;
setInterval(() => {
  currentBPM = Math.floor(60 + Math.random() * 100);
  setBPM(currentBPM);
}, 2000);

function toggleSidebar() {
    const nav = document.querySelector("nav");
    const container = document.querySelector(".container");

    nav.classList.toggle("active");
    container.classList.toggle("shift");
}