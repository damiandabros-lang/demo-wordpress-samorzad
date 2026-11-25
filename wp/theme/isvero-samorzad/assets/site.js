(function(){
  var html=document.documentElement;
  function store(k,v){try{localStorage.setItem(k,v)}catch(e){}}
  var hc=document.getElementById('hc-toggle');
  if(hc){hc.setAttribute('aria-pressed',html.classList.contains('hc')?'true':'false');
    hc.addEventListener('click',function(){var on=html.classList.toggle('hc');hc.setAttribute('aria-pressed',on?'true':'false');store('isv-hc',on?'1':'0');});}
  var fsBtns=document.querySelectorAll('[data-fs]');
  function setFs(n){html.classList.remove('fs1','fs2');if(n!=='0')html.classList.add('fs'+n);
    fsBtns.forEach(function(b){b.setAttribute('aria-pressed',b.getAttribute('data-fs')===n?'true':'false')});store('isv-fs',n);}
  var cur=html.classList.contains('fs2')?'2':html.classList.contains('fs1')?'1':'0';
  fsBtns.forEach(function(b){b.setAttribute('aria-pressed',b.getAttribute('data-fs')===cur?'true':'false');b.addEventListener('click',function(){setFs(b.getAttribute('data-fs'))})});
  var t=document.querySelector('.nav-toggle'),m=document.getElementById('menu-glowne');
  if(t&&m){t.addEventListener('click',function(){var o=m.classList.toggle('open');t.setAttribute('aria-expanded',o?'true':'false');});
    document.addEventListener('keydown',function(e){if(e.key==='Escape'&&m.classList.contains('open')){m.classList.remove('open');t.setAttribute('aria-expanded','false');t.focus();}});}
  var st=document.getElementById('form-status');if(st)st.focus();
})();
