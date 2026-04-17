// ── Match Data ────────────────────────────────────────────────
let matches = { cricket: [], football: [], f1: [] };

// ── Ticker ────────────────────────────────────────────────────
function buildTicker(){
  const live = Object.values(matches).flat().filter(m=>m.status==='live');
  if(live.length === 0) {
      document.getElementById('tickerTrack').innerHTML = '<div class="ticker-item"><strong>No live matches right now</strong></div>';
      return;
  }
  const items = [...live,...live].map(m=>`
    <div class="ticker-item">
      <strong>${m.home.name}</strong>
      <span>${m.home.score||'—'}</span>
      <span style="color:var(--live);font-weight:700">vs</span>
      <span>${m.away.score||'—'}</span>
      <strong>${m.away.name}</strong>
      <span class="ticker-dot"></span>
      <span>${m.league}</span>
    </div>
  `).join('');
  document.getElementById('tickerTrack').innerHTML = items;
}

// ── Card Builder ───────────────────────────────────────────────
function sportEmoji(s){return{cricket:'🏏',football:'⚽',f1:'🏎'}[s]||'🏆'}

function buildCard(m){
  const isLocked = m.pro && !currentUser;
  const statusMap = {live:'🔴 Live',upcoming:'🕐 Upcoming',completed:'✔ Completed'};
  const actionMap = {
    live:   isLocked ? '🔒 Pro Only'  : '▶ Watch Live',
    upcoming: isLocked ? '🔒 Pro Only' : '🔔 Set Reminder',
    completed: isLocked ? '🔒 Pro Only' : '▶ Watch Replay',
  };

  const card = document.createElement('div');
  card.className = `match-card${m.status==='live'?' live-card':''}`;
  card.dataset.sport = m.sport;
  card.dataset.status = m.status;
  card.dataset.id = m.id;
  card.innerHTML = `
    <div class="card-stripe ${m.sport}"></div>
    <div class="card-body">
      <div class="card-meta">
        <div class="card-sport">
          <span class="sport-dot ${m.sport}"></span>
          ${m.league}
        </div>
        <div class="status-badge ${m.status}">${statusMap[m.status]}</div>
      </div>
      <div class="card-teams">
        <div class="team">
          <div class="team-logo">${m.home.flag}</div>
          <div class="team-name">${m.home.name}</div>
          ${m.status!=='upcoming'?`<div class="team-score">${m.home.score}</div>`:'<div class="team-score" style="color:var(--muted)">—</div>'}
          ${m.home.sub?`<div class="team-sub">${m.home.sub}</div>`:''}
        </div>
        <div class="vs">
          <div class="vs-text">VS</div>
          <div class="vs-time">${m.time}</div>
        </div>
        <div class="team">
          <div class="team-logo">${m.away.flag}</div>
          <div class="team-name">${m.away.name}</div>
          ${m.status!=='upcoming'?`<div class="team-score">${m.away.score}</div>`:'<div class="team-score" style="color:var(--muted)">—</div>'}
          ${m.away.sub?`<div class="team-sub">${m.away.sub}</div>`:''}
        </div>
      </div>
    </div>
    <div class="card-footer">
      <div class="card-info">
        <span>${sportEmoji(m.sport)}</span>
        <span>${m.venue}</span>
      </div>
      <div class="card-action${isLocked?' locked':''}">
        ${actionMap[m.status]}
      </div>
    </div>
    ${isLocked?`
    <div class="locked-overlay show">
      <div class="locked-msg">
        <div class="lock-icon">🔒</div>
        <p>This match requires a Pro subscription.</p>
        <button class="btn btn-accent" onclick="openModal('register');event.stopPropagation()">Upgrade</button>
      </div>
    </div>`:''}
  `;
  card.addEventListener('click', ()=>handleCardClick(m));
  return card;
}

function handleCardClick(m){
  if(m.pro && !currentUser){
    openModal('register');
    return;
  }
  const labels = {live:'Connecting to live stream…',upcoming:'Reminder set! ✅',completed:'Loading replay…'};
  showToast(labels[m.status],'info');
}

// ── Render All ────────────────────────────────────────────────
function renderAll(filter='all'){
  ['cricket','football','f1'].forEach(sport=>{
    const grid = document.getElementById(`${sport}-grid`);
    if(!grid) return;
    grid.innerHTML = '';
    let filtered = matches[sport] || [];
    if(filter!=='all') filtered = filtered.filter(m=>m.status===filter);

    // search
    const q = document.getElementById('searchInput').value.trim().toLowerCase();
    if(q) filtered = filtered.filter(m=>
      m.home.name.toLowerCase().includes(q) ||
      m.away.name.toLowerCase().includes(q) ||
      m.league.toLowerCase().includes(q)
    );

    document.getElementById(`${sport}-count`).textContent = filtered.length;

    filtered.forEach((m,i)=>{
      const card = buildCard(m);
      grid.appendChild(card);
      setTimeout(()=>{card.classList.add('visible')},i*80+100);
    });

    const sec = document.getElementById(`sec-${sport}`);
    sec.style.display = filtered.length===0?'none':'block';
    setTimeout(()=>sec.classList.add('visible'),150);
  });
}

// ── Filter Pills ──────────────────────────────────────────────
let activeFilter = 'all';
document.getElementById('filterPills').addEventListener('click',e=>{
  const pill = e.target.closest('.pill');
  if(!pill) return;
  document.querySelectorAll('.pill').forEach(p=>p.classList.remove('active'));
  pill.classList.add('active');
  activeFilter = pill.dataset.filter;
  renderAll(activeFilter);
});

function filterBy(sport){
  document.querySelectorAll('.pill').forEach(p=>p.classList.remove('active'));
  document.querySelector('.pill[data-filter="all"]').classList.add('active');
  activeFilter = 'all';
  renderAll('all');
  setTimeout(()=>{
    document.getElementById(`sec-${sport}`).scrollIntoView({behavior:'smooth',block:'start'});
  },100);
}

function scrollToMatches(){
  document.getElementById('sec-cricket').scrollIntoView({behavior:'smooth'});
}

// ── Search ────────────────────────────────────────────────────
let searchTimer;
document.getElementById('searchInput').addEventListener('input',()=>{
  clearTimeout(searchTimer);
  searchTimer = setTimeout(()=>renderAll(activeFilter),250);
});

// ── Nav Tabs ──────────────────────────────────────────────────
document.querySelectorAll('.nav-tab,.mobile-tab').forEach(tab=>{
  tab.addEventListener('click',()=>{
    document.querySelectorAll('.nav-tab,.mobile-tab').forEach(t=>t.classList.remove('active'));
    document.querySelectorAll(`[data-tab="${tab.dataset.tab}"]`).forEach(t=>t.classList.add('active'));

    const t = tab.dataset.tab;
    if(t==='home'){ renderAll(activeFilter); scrollToTop(); }
    else if(['cricket','football','f1'].includes(t)){ filterBy(t); }
    else if(t==='scores'){
      document.querySelectorAll('.pill').forEach(p=>p.classList.remove('active'));
      document.querySelector('.pill[data-filter="live"]').classList.add('active');
      activeFilter='live';
      renderAll('live');
      scrollToMatches();
    }
    closeMobileMenu();
  });
});

function scrollToTop(){ window.scrollTo({top:0,behavior:'smooth'}); }

// ── Mobile Menu ───────────────────────────────────────────────
function toggleMobileMenu(){
  document.getElementById('mobileMenu').classList.toggle('open');
}
function closeMobileMenu(){
  document.getElementById('mobileMenu').classList.remove('open');
}

// ── Avatar Menu ───────────────────────────────────────────────
document.getElementById('avatarBtn')?.addEventListener('click',e=>{
  e.stopPropagation();
  document.getElementById('avatarMenu').classList.toggle('open');
});
document.addEventListener('click',()=>{
  document.getElementById('avatarMenu')?.classList.remove('open');
});

// ── Auth UI ───────────────────────────────────────────────────
function setAuthUI(user){
  currentUser = user;
  if(user){
    document.getElementById('authButtons').style.display='none';
    const um = document.getElementById('userMenu');
    um.style.display='flex';
    document.getElementById('avatarBtn').textContent = user.avatar || user.name[0].toUpperCase();
    document.getElementById('menuName').innerHTML =
      `${user.name} <span class="plan-badge ${user.plan==='Pro'?'pro':'free'}">${user.plan}</span><br>
       <span class="avatar-menu-sub">${user.email}</span>`;
    document.getElementById('menuEmail').textContent = user.email;
    document.getElementById('heroJoinBtn').textContent = '▶ Start Watching';
    renderAll(activeFilter); // re-render to un-lock cards
  } else {
    document.getElementById('authButtons').style.display='flex';
    document.getElementById('userMenu').style.display='none';
    renderAll(activeFilter);
  }
}

// ── Modal ─────────────────────────────────────────────────────
function openModal(type){
  const overlay = document.getElementById('overlay');
  overlay.classList.add('open');
  document.getElementById('loginModal').style.display    = type==='login'    ? 'block' : 'none';
  document.getElementById('registerModal').style.display = type==='register' ? 'block' : 'none';
  clearErrors();
}
function closeModal(){
  document.getElementById('overlay').classList.remove('open');
  clearErrors();
}
function closeModalOutside(e){
  if(e.target.id==='overlay') closeModal();
}
function clearErrors(){
  ['loginError','registerError'].forEach(id=>{
    const el=document.getElementById(id);
    el.style.display='none';el.textContent='';el.classList.remove('show');
  });
}
function showError(id,msg){
  const el=document.getElementById(id);
  el.textContent=msg;el.style.display='block';
  requestAnimationFrame(()=>el.classList.add('show'));
}

// ── Login ─────────────────────────────────────────────────────
async function doLogin(){
  const email = document.getElementById('loginEmail').value.trim();
  const pass  = document.getElementById('loginPass').value;
  if(!email||!pass){ showError('loginError','Please fill in all fields.'); return; }

  const btn = document.getElementById('loginBtn');
  btn.textContent='Signing in…';btn.disabled=true;

  try{
    const fd = new FormData();
    fd.append('action','login'); fd.append('email',email); fd.append('password',pass);
    const res = await fetch('api/auth.php', {method:'POST', body:fd});
    const data = await res.json();
    if(data.ok){
      closeModal();
      setAuthUI(data.user);
      showToast(`Welcome back, ${data.user.name}! 👋`,'success');
    } else {
      showError('loginError', data.msg);
    }
  }catch(e){ showError('loginError','Network error. Please try again.'); }
  finally{ btn.textContent='Sign In'; btn.disabled=false; }
}

// ── Register ──────────────────────────────────────────────────
async function doRegister(){
  const name  = document.getElementById('regName').value.trim();
  const email = document.getElementById('regEmail').value.trim();
  const pass  = document.getElementById('regPass').value;
  if(!name||!email||!pass){ showError('registerError','Please fill in all fields.'); return; }

  const btn = document.getElementById('registerBtn');
  btn.textContent='Creating account…'; btn.disabled=true;

  try{
    const fd = new FormData();
    fd.append('action','register'); fd.append('name',name);
    fd.append('email',email); fd.append('password',pass);
    const res = await fetch('api/auth.php', {method:'POST', body:fd});
    const data = await res.json();
    if(data.ok){
      closeModal();
      setAuthUI(data.user);
      showToast(`Welcome to SSNAPP, ${data.user.name}! 🎉`,'success');
    } else {
      showError('registerError', data.msg);
    }
  }catch(e){ showError('registerError','Network error. Please try again.'); }
  finally{ btn.textContent='Create Account'; btn.disabled=false; }
}

// ── Logout ────────────────────────────────────────────────────
async function doLogout(){
  const fd=new FormData(); fd.append('action','logout');
  await fetch('api/auth.php',{method:'POST',body:fd});
  setAuthUI(null);
  showToast('You have been signed out.','info');
}

// ── Enter key on modals ───────────────────────────────────────
document.getElementById('loginPass').addEventListener('keydown',e=>e.key==='Enter'&&doLogin());
document.getElementById('regPass').addEventListener('keydown',e=>e.key==='Enter'&&doRegister());

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg, type='info'){
  const icons = {success:'✅', error:'❌', info:'ℹ️'};
  const t = document.createElement('div');
  t.className=`toast ${type}`;
  t.innerHTML=`<span class="toast-icon">${icons[type]}</span><span>${msg}</span>`;
  document.getElementById('toast-container').appendChild(t);
  setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateX(20px)';
    t.style.transition='all .3s'; setTimeout(()=>t.remove(),300); }, 3500);
}

// ── Intersection Observer (reveal) ───────────────────────────
const observer = new IntersectionObserver(entries=>{
  entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('visible'); });
},{threshold:.1});
document.querySelectorAll('.sport-section').forEach(s=>observer.observe(s));

// ── Init ──────────────────────────────────────────────────────
async function init(){
  try {
    const res = await fetch('api/matches.php');
    if(res.ok) {
        matches = await res.json();
    }
  } catch (e) {
    console.error("Could not fetch matches:", e);
  }
  
  buildTicker();
  renderAll('all');
  if(typeof currentUser !== 'undefined' && currentUser) setAuthUI(currentUser);
}

init();
