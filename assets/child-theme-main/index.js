import './styles/index.scss';
import './navigation-bar';

let prefbutton = document.getElementById('preferences');
prefbutton.addEventListener('click', showHide,{passive: true});

function showHide() {
  let settings = document.getElementById('cookieSettings');
  let defaultBtn = document.getElementById('hidecookie');
  let consenttxt = document.getElementById('consent');
  settings.style.display = settings.style.display==='none' ? 'flex' : 'none';
  defaultBtn.style.display = defaultBtn.style.length===0 ? 'inline-block' : 'none';
  defaultBtn.style.display = defaultBtn.style.display==='inline-block' ? 'none' : 'inline-block';
  consenttxt.textContent = consenttxt.textContent==='OK!' ? 'Alle cookies' : 'OK!';
}

document.getElementById('all').addEventListener('click', consent,{passive: true});
document.getElementById('functional').addEventListener('click', consent,{passive: true});
document.getElementById('none').addEventListener('click', consent,{passive: true});
function consent(e) {
  let cookieBar = document.getElementById('set-cookie');
  cookieBar.classList.remove('shown');


  switch (e.target.id) {
  case 'all':
    console.log(e.target.id);
    break;
  case 'functional':
    console.log(e.target.id);
    break;
  case 'none':
    console.log(e.target.id);
    break;
  }
}
