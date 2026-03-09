const patientName = document.getElementById("patientName");
const bpText = document.getElementById("bpValue");
const hrText = document.getElementById("hrValue");
const o2Text = document.getElementById("o2Value");
const timeText = document.getElementById("timeValue");
const carousel = document.getElementById("patientCarousel");

const ring = document.getElementById("bpRing");
const circumference = 440;

let patients = [];
let index = 0;

function showPatient(){

    if(patients.length === 0) return;

    let p = patients[index];

    patientName.innerText = p.pat_name;

    bpText.innerText = p.bp_systolic + "/" + p.bp_diastolic;
    hrText.innerText = p.heart_rate;
    o2Text.innerText = p.oxygen_level;

    let date = new Date(p.recorded_at);
    timeText.innerText = date.toLocaleString();

    let percent = Math.min(p.bp_systolic / 180,1);
    ring.style.strokeDashoffset = circumference * (1-percent);

    if(p.bp_systolic >= 140) ring.style.stroke="#ef4444";
    else if(p.bp_systolic >= 120) ring.style.stroke="#f59e0b";
    else ring.style.stroke="#22c55e";

    Array.from(carousel.children).forEach((c,i)=>
        c.classList.toggle("active",i===index)
    );

    index++;
    if(index >= patients.length) index = 0;
}

function loadPatients(){

    fetch("Responder_mobile/api/bp_live.php")
    .then(res=>res.json())
    .then(data=>{

        patients = data;

        carousel.innerHTML = "";

        patients.forEach((p,i)=>{
            let div = document.createElement("div");
            div.innerText = p.pat_name;
            carousel.appendChild(div);
        });

        if(patients.length > 0){
            showPatient();
            setInterval(showPatient,3000);
        }

    });

}

loadPatients();