$(function () {
  const body = document.body;
  if (localStorage.getItem('darkMode') === '1') body.classList.add('dark');

  $('#darkMode').on('click', function () {
    body.toggleClass('dark');
    localStorage.setItem('darkMode', body.hasClass('dark') ? '1' : '0');
  });

  function showMsg(target, text, ok=true) {
    $(target).html('<div class="alert ' + (ok ? 'success':'danger') + '">' + text + '</div>').hide().fadeIn(180);
    setTimeout(() => $(target).fadeOut(250), 2600);
  }

  // MENU CRUD + search/sort
  function loadMenu() {
    if (!$('#menuBody').length) return;
    $.getJSON('api/menu.php', {action:'list', q:$('#menuSearch').val(), sort:$('#menuSort').val()}, function(r) {
      if (!r.ok) return;
      let html='';
      r.data.forEach(x => {
        html += `<tr>
          <td>${x.id}</td><td><b>${esc(x.name)}</b></td><td>${esc(x.category)}</td>
          <td>Rs. ${Number(x.price).toFixed(2)}</td><td>${x.stock}</td><td><span class="badge">${x.status}</span></td>
          <td><button class="small editMenu" data-row='${JSON.stringify(x)}'>Edit</button>
          <button class="small dangerBtn deleteMenu" data-id="${x.id}">Delete</button></td>
        </tr>`;
      });
      $('#menuBody').html(html || '<tr><td colspan="7" class="muted">No matching records.</td></tr>');
    });
  }
  function esc(s){ return $('<div>').text(s).html(); }

  let timer;
  $('#menuSearch').on('input', function(){ clearTimeout(timer); timer=setTimeout(loadMenu,250); });
  $('#menuSort').on('change', loadMenu);

  $('#openMenuModal').on('click', function(){ $('#menuForm')[0].reset(); $('#menuId').val(''); $('#modalTitle').text('Add Menu Item'); $('#menuModal').addClass('show'); });
  $('#closeMenuModal').on('click', ()=>$('#menuModal').removeClass('show'));
  $(document).on('click','.editMenu',function(){
    const x=$(this).data('row');
    $('#menuId').val(x.id); $('#menuName').val(x.name); $('#menuCategory').val(x.category); $('#menuPrice').val(x.price); $('#menuStock').val(x.stock); $('#menuStatus').val(x.status);
    $('#modalTitle').text('Edit Menu Item'); $('#menuModal').addClass('show');
  });
  $('#menuForm').on('submit', function(e){
    e.preventDefault();
    $.post('api/menu.php', $(this).serialize()+'&action=save', function(r){
      showMsg('#menuMsg',r.message,r.ok); if(r.ok){$('#menuModal').removeClass('show');loadMenu();}
    },'json').fail(xhr=>showMsg('#menuMsg',xhr.responseJSON?.message||'Server error',false));
  });
  $(document).on('click','.deleteMenu',function(){
    if(!confirm('Delete this menu item?')) return;
    $.post('api/menu.php',{action:'delete',id:$(this).data('id')},function(r){showMsg('#menuMsg',r.message,r.ok);loadMenu();},'json')
    .fail(xhr=>showMsg('#menuMsg',xhr.responseJSON?.message||'Delete failed',false));
  });
  loadMenu();

  // ORDERS AJAX
  function loadOrders(){
    if(!$('#orderBody').length) return;
    $.getJSON('api/orders.php',{action:'list',q:$('#orderSearch').val(),sort:$('#orderSort').val()},function(r){
      let html='';
      r.data.forEach(x=>{
        html += `<tr><td>#${x.id}</td><td>${esc(x.customer_name)}</td><td>${esc(x.item_name)}</td><td>${x.quantity}</td>
        <td>Rs. ${Number(x.subtotal).toFixed(2)}</td><td>${x.discount_rate}%</td><td><b>Rs. ${Number(x.total).toFixed(2)}</b></td>
        <td><select class="statusSelect" data-id="${x.id}">${['Pending','Preparing','Completed','Cancelled'].map(s=>`<option ${s===x.order_status?'selected':''}>${s}</option>`).join('')}</select></td>
        <td>${x.order_status==='Completed'?'✓':'—'}</td></tr>`;
      });
      $('#orderBody').html(html||'<tr><td colspan="9" class="muted">No orders found.</td></tr>');
    });
  }
  let ot;
  $('#orderSearch').on('input',function(){clearTimeout(ot);ot=setTimeout(loadOrders,250);});
  $('#orderSort').on('change',loadOrders);
  $('#openOrderModal').on('click',()=>$('#orderModal').addClass('show'));
  $('#closeOrderModal').on('click',()=>$('#orderModal').removeClass('show'));
  $('#orderForm').on('submit',function(e){
    e.preventDefault();
    $.post('api/orders.php',$(this).serialize()+'&action=create',function(r){
      showMsg('#orderMsg',r.message,r.ok); if(r.ok){$('#orderModal').removeClass('show');$('#orderForm')[0].reset();$('#orderPreview').text('Total: Rs. 0.00');loadOrders();}
    },'json').fail(xhr=>showMsg('#orderMsg',xhr.responseJSON?.message||'Order failed',false));
  });
  $('#orderItem,#orderQty').on('change input',function(){
    const p=Number($('#orderItem option:selected').data('price')||0), q=Math.max(0,Number($('#orderQty').val()||0));
    const sub=p*q, rate=sub>10000?15:(sub>5000?10:0), total=sub-(sub*rate/100);
    $('#orderPreview').html(`Subtotal: Rs. ${sub.toFixed(2)} · Discount: ${rate}% · <b>Total: Rs. ${total.toFixed(2)}</b>`);
  });
  $(document).on('change','.statusSelect',function(){
    $.post('api/orders.php',{action:'status',id:$(this).data('id'),status:this.value},function(r){showMsg('#orderMsg',r.message,r.ok);loadOrders();},'json');
  });
  loadOrders();
});
