const bpmCircle = document.querySelector('.ring-progress');
const bpmText = document.getElementById('bpm');

if(bpmCircle){

const radius = bpmCircle.r.baseVal.value;
const circumference = 2 * Math.PI * radius;

bpmCircle.style.strokeDasharray = circumference;
bpmCircle.style.strokeDashoffset = circumference;

function setBPM(bpm){

bpmText.textContent = bpm;

/* Color logic */
let color = "#2ecc71";

if(bpm > 120){
color = "#e74c3c";
}
else if(bpm > 90){
color = "#f1c40f";
}

bpmCircle.style.stroke = color;

/* Progress movement */
const percent = Math.min(bpm / 200,1);
const offset = circumference * (1 - percent);

bpmCircle.style.strokeDashoffset = offset;

}

/* Simulated data */
let currentBPM = 60;

setInterval(()=>{
currentBPM = Math.floor(60 + Math.random()*100);
setBPM(currentBPM);
},2000);

}