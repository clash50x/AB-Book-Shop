document.addEventListener('DOMContentLoaded',()=>{
  const btn=document.getElementById('menuBtn'), menu=document.getElementById('menu');
  if(btn&&menu) btn.addEventListener('click',()=>menu.classList.toggle('open'));
  setTimeout(()=>document.querySelectorAll('.toast').forEach(t=>t.style.transition='opacity .4s',t=>t.style.opacity='0'),3500);
});
