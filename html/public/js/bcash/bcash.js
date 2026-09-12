// CSRF Token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var client=null;
var products = {
  // (A) DEPOSIT LIST
  list : {
    1 : { name:"Loan Installment",post_name:'Loan_Installment',charge_type:'Loan_Installment',group:"Real_Estate_Loan",company:"Real Estate", img:"contract.png", value: 1 },
    2 : { name:"Deposit",post_name:'Deposit',charge_type:'Deposit', group:"Real_Estate_Loan",company:"Real Estate", img:"contract.png", value: 1 },
    3 : { name:"Down-Payment",post_name:'Down_Payment',charge_type:'Down_Payment', group:"Real_Estate_Loan",company:"Real Estate", img:"contract.png", value: 1 },
    4 : { name:"Pay-Off",post_name:'Pay_Off',charge_type:'Pay_Off', group:"Real_Estate_Loan", img:"contract.png",company:"Real Estate", value: 1 },
    5 : { name:"Penalty Fee",post_name:'Penalty_Fee',charge_type:'Penalty_Fee',group:"Real_Estate_Loan", img:"Penalty_Fee.png",company:"Real Estate", value: 1 },
    6 : { name:"Admin Fee-Sub Sale",post_name:'Admin_Fee_Sub_Sale',charge_type:'charge_type',group:"Real_Estate_Loan",company:"Real Estate", img:"charge_type.png", value: 10 },
    7 : { name:"Admin Fee-Owner Ship",post_name:'Admin_Fee_Owner_Ship',charge_type:'charge_type',group:"Real_Estate_Loan", company:"Real Estate",img:"charge_type.png", value: 11 },
    8 : { name:"Admin Fee Reschdule",post_name:'Admin_Fee_Reschdule',charge_type:'charge_type', group:"Real_Estate_Loan",company:"Real Estate",img:"charge_type.png", value: 2 },
    9 : { name:"Admin Fee Change Unit",post_name:'Admin_Fee_Change_Unit',charge_type:'charge_type',group:"Real_Estate_Loan",company:"Real Estate", img:"charge_type.png", value: 1 },
    10: { name:"Tittle Transfer Fee",post_name:'Tittle_Transfer_Fee',charge_type:'charge_type',group:"Real_Estate_Other_Fee",company:"Real Estate", img:"charge_type.png", value: 7 },
    11: { name:"Stamp Tax Fee",post_name:'Stamp_Tax_Fee',charge_type:'charge_type',group:"Real_Estate_Other_Fee",company:"Real Estate", img:"charge_type.png", value: 7 },
    12: { name:"Renovation Fee",post_name:'Renovation_Fee',charge_type:'charge_type',group:"Real_Estate_Other_Fee",company:"Real Estate", img:"charge_type.png", value: 7 },
    13 : { name:"Electricity Fee",post_name:'Electricity_Fee',charge_type:'charge_type',group:"Property_Company",company:"Property", img:"charge_type.png", value: 5 },
    14 : { name:"Sport Club Fee",post_name:'Sport_Club_Fee',charge_type:'charge_type',group:"Property_Company",company:"Property", img:"charge_type.png", value: 5 },
    15 : { name:"Water Fee",post_name:'Water_Fee',charge_type:'charge_type',group:"Property_Company",company:"Property", img:"charge_type.png", value: 6 },
    16 : { name:"Rental Fee",post_name:'Rental_Fee',charge_type:'charge_type',group:"Property_Company",company:"Property", img:"charge_type.png", value: 6 },
    17 : { name:"Maintenance Fee",post_name:'Maintenance_Fee',charge_type:'charge_type',group:"Property_Company",company:"Property", img:"charge_type.png", value: 4 },
    18 : { name:"Entrance Card Fee",post_name:'Entrance_Card_Fee',charge_type:'charge_type',group:"Property_Company",company:"Property", img:"charge_type.png", value: 4 },
    19 : { name:"Internet Service Fee",post_name:'Internet_Service_Fee',charge_type:'charge_type',group:"IIP",company:"IIP", img:"wifi.png", value: 4 },
    20 : { name:"CCTV Fee",post_name:'CCTV_Fee',charge_type:'charge_type',group:"IIP", img:"camera.png",company:"IIP", value: 4 },
  },

  // (B) DRAW HTML PRODUCTS LIST
  draw : () => {
    // (B1) TARGET WRAPPER
    const wrapper = document.getElementById("poslist");

    // (B2) CREATE PRODUCT HTML
    for (let pid in products.list) {
      // CURRENT PRODUCT
      let p = products.list[pid],
          pdt = document.createElement("div"),
          segment;
      // PRODUCT SEGMENT
      pdt.className = "pwrap"+' '+p.group;
      pdt.onclick = () => { cart.add(pid); };
      wrapper.appendChild(pdt);

      // IMAGE
      segment = document.createElement("img");
      segment.className = "pimg";
      segment.src = "/images/" + p.img;
      pdt.appendChild(segment);

      // NAME
      segment = document.createElement("div");
      segment.className = "pname";
      segment.innerHTML = p.name;
      pdt.appendChild(segment);
    }
  }
};
window.addEventListener("DOMContentLoaded", products.draw);

var cart = {
  // (A) PROPERTIES
  items : {}, // CURRENT ITEMS IN CART

  // (B) SAVE CURRENT CART INTO LOCALSTORAGE
  save : () => {
    localStorage.setItem("cart", JSON.stringify(cart.items));
  },

  // (C) LOAD CART FROM LOCALSTORAGE
  load : () => {
    cart.items = localStorage.getItem("cart");
    if (cart.items == null) { cart.items = {}; }
    else { cart.items = JSON.parse(cart.items); }
  },

  // (D) NUKE CART!
  nuke : () => {
    cart.items = {};
    localStorage.removeItem("cart");
    cart.list();
  },

  // (E) INITIALIZE - RESTORE PREVIOUS SESSION
  init : () => {
    cart.load();
    cart.list();
  },

  // (F) LIST CURRENT CART ITEMS (IN HTML)
  list : () => {
    // (F1) DRAW CART INIT
    var wrapper = document.getElementById("poscart"),
        item, part, pdt,
        total = 0, subtotal = 0,
        empty = true;
    wrapper.innerHTML = "";
    for (let key in cart.items) {
      if (cart.items.hasOwnProperty(key)) { empty = false; break; }
    }

    // (F2) CART IS EMPTY
    if (empty) {
      item = document.createElement("tr");
      itemTD = document.createElement("td");
      item.appendChild(itemTD);
      itemTD.setAttribute("colspan", "4");
      itemTD.innerHTML = "Cart is empty";
      wrapper.appendChild(item);
    }

    // (F3) CART IS NOT EMPTY - LIST ITEMS
    else {
      let index=0;
      for (let pid in cart.items) {
        index++;
        // CURRENT ITEM
        pdt = products.list[pid];
        item = document.createElement("tr");
        item.className = "deposit_type";
        wrapper.appendChild(item);

        // ITEM INDEX
        part = document.createElement("td");
        part.innerHTML = index;
        part.setAttribute("width", "50px");
        item.appendChild(part);

        // ITEM NAME
        part = document.createElement("td");
        part.innerHTML = pdt.name;
        item.appendChild(part);

        // ITEM AMOUNT
        part = document.createElement("td");
        
            // AMOUNT
            part_amount = document.createElement("input");
            part_amount.type = "number";
            part_amount.name =pdt.post_name;
            part_amount.min = 0;
            part_amount.value=cart.items[pid];
            part_amount.id="input_amount";
            part_amount.className = "amount form-control";
            part_amount.onchange = function () { cart.change(pid, this.value,part_amount.name); };
            part.appendChild(part_amount);

            // COMPANY
            part_company = document.createElement("input");
            part_company.type = "hidden";
            part_company.name ="deposit_company";
            part_company.value=pdt.company;
            part_company.id="input_deposit_company";
            part_company.className = "form-control";
            part.appendChild(part_company);

                        
        item.appendChild(part);

        // DESCRIPTION
        part = document.createElement("td");
          part_description = document.createElement("textarea");
          part_description.type = "text";
          part_description.name='note_'+pdt.post_name;
          part_description.value=pdt.name;
          part_description.row=1;
          part_description.innerHTML=pdt.name;
          part_description.className = "description form-control";
          part.appendChild(part_description);
        item.appendChild(part);

        // REMOVE
        part = document.createElement("td");
          partDel = document.createElement("input");
          partDel.type = "button";
          partDel.value = "X";
          partDel.className = "cdel";
          partDel.onclick = () => { cart.remove(pid); };
          part.appendChild(partDel);
        item.appendChild(part);

        // SUBTOTAL
        subtotal = cart.items[pid] * pdt.value;
        total += subtotal;
      }
      
      wrapper.appendChild(item);
    }
  },

  // (G) ADD ITEM TO CART
  add : (pid) => {
    if (cart.items[pid] == undefined) { cart.items[pid] = ''; }
    // else { cart.items[pid]++; }
    cart.save(); cart.list();
  },

  // (H) CHANGE QUANTITY
  change : (pid, amount,name) => {
    // (H1) REMOVE ITEM
    if (amount <= 0) {
      delete cart.items[pid];
      cart.save(); cart.list();
    }

    // (H2) UPDATE TOTAL ONLY
    else {
      cart.items[pid] = amount;
      GrandTotal(name); 
      // var total = 0;
      // for (let id in cart.items) {
      //   total += cart.items[pid] * products.list[pid].price;
      //   document.getElementById("ctotal").innerHTML ="TOTAL: $" + total;
      // }
    }
  },

  // (I) REMOVE ITEM FROM CART
  remove : (pid) => {
    delete cart.items[pid];
    cart.save(); cart.list();
  },

  // (J) CHECKOUT
  checkout : () => {
    // orders.print();
    orders.add();
  }
};
window.addEventListener("DOMContentLoaded", cart.init);

var orders = {
  // (A) PROPERTY
  idb : window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB,
  posdb : null,
  db : null,

  // (A) INIT - CREATE DATABASE
  init : () => {
    // (A1) INDEXED DATABASE OBJECT
    if (!orders.idb) {
      alert("INDEXED DB IS NOT SUPPORTED ON THIS BROWSER!");
      return false;
    }

    // (A2) OPEN POS DATABASE
    orders.posdb = orders.idb.open("JSPOS", 1);
    orders.posdb.onsuccess = () => {
      orders.db = orders.posdb.result;
    };

    // (A3) CREATE POS DATABASE
    orders.posdb.onupgradeneeded = () => {
      // ORDERS STORE (TABLE)
      var db = orders.posdb.result,
      store = db.createObjectStore("Orders", {keyPath: "oid", autoIncrement: true}),
      index = store.createIndex("time", "time");

      // ORDER ITEMS STORE (TABLE)
      store = db.createObjectStore("Items", {keyPath: ["oid", "pid"]}),
      index = store.createIndex("qty", "qty");
    };

    // (A4) ERROR!
    orders.posdb.onerror = (err) => {
      alert("ERROR CREATING DATABASE!");
      console.error(err);
    };
  },

  // (B) ADD NEW ORDER
  add : () => {
    // $("#client").removeClass( "validation" );
    // if(client===null){
    //   $("#client").addClass( "validation" );
    //   $("#client").val('');
    //   return;
    // }
    // var deposit_type=[];
    // $('#poscart').find('.deposit_type').each(function() {
    //     var amount=$(this).find("input[class*='amount']").val();
    //     if(amount==='' || amount<=0){
    //       $(this).find("input[class*='amount']").addClass( "validation" );
    //       return;
    //     }else{
    //       $(this).find("input[class*='amount']").removeClass( "validation" );
    //     } 
        
        // $( "#addBCashLoanPaymentForm" ).submit();

        // var charge_type_val=$(this).find("input[class*='charge_type']").val();
        // var charge_type=$(this).find("input[class*='charge_type']").attr("name");
        // var note=$(this).find('textarea[name="note"]').val();
      
        // deposit_type.push(
        // {
        //   name:charge_type,
        //   value:charge_type_val,
        //   charge_amount:amount,
        //   description:note
        // });
      // });
        // var parent_debit = $('input[name="parent_debit[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // var debit = $('input[name="debit[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // var d_description = $('input[name="d_description[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // var parent_credit = $('input[name="parent_credit[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // var credit = $('input[name="credit[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // var c_description = $('input[name="c_description[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // var description = $('textarea[name="description[]"]').map(function(){ 
        //   return this.value; 
        // }).get();
        // $.ajax({
        //   type:'POST',
        //   url:'/bcash/addBcash/'+client.loan_id,
        //   headers: {
        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // },
        //   data: {
        //       client,
        //       deposit_type,
        //       'parent_debit[]': parent_debit,
        //       'debit[]':debit,
        //       'd_description[]':d_description,
        //       'parent_credit[]':parent_credit,
        //       'credit[]':credit,
        //       'c_description[]':c_description,
        //       'description[]':description
        //     },
        //     dataType: 'json',
        //     success: function (data) {
        //       console.log(data);
        //     },
        //     error: function (data) {
        //         console.log(data);
        //     }
        // });
  },

  // (C) PRINT RECEIPT FOR CURRENT ORDER
  print : () => {
    // (C1) GENERATE RECEIPT
    var wrapper = document.getElementById("posreceipt");
    wrapper.innerHTML = "";
    for (let pid in cart.items) {
      let item = document.createElement("div");
      item.innerHTML = `${cart.items[pid]} X ${products.list[pid].name}`;
      wrapper.appendChild(item);
    }

    // (C2) PRINT
    var printwin = window.open();
    printwin.document.write(wrapper.innerHTML);
    printwin.stop();
    printwin.print();
    printwin.close();
  }
};
window.addEventListener("DOMContentLoaded", orders.init);
function clearCart(){
  cart.nuke();
}
function checkout(){
  cart.checkout();
}
function GrandTotal(){
  var total=0;
  var total_installment=0;
  var totalBalance=0;
  var total_penalty = 0;
  var repayment_amount=0;
  var sub_total=0;
  var cound_deposit=0;
  var item_deposit_list='';
  $('#poscart').find('.deposit_type').each(function() {
      var amount=$(this).find("input[class*='amount']").val();
      var name = $(this).find("input[class*='amount']").attr("name");
      if(amount!==''){
        total=parseFloat(total)+parseFloat(amount);        
      }
      item_deposit_list+='<tr><th scope="row">'+name.replace(/_/g, ' ')+'</th><td>(+) $<span id="res-total-penalty"> '+parseFloat(amount)+'</span></td></tr>'; 

      cound_deposit++;
      switch(name) {
        case "Loan_Installment":
        case "Deposit":
        case "Down_Payment":
        case "Pay_Off":
          total_installment = parseFloat(total_installment)+parseFloat(amount);
          totalBalance=parseFloat(totalBalance)+parseFloat(amount);
          sub_total= parseFloat(sub_total)+parseFloat(amount);
          break;
        case "Penalty_Fee":
            total_penalty = parseFloat(total_penalty)+parseFloat(amount);
            repayment_amount=repayment_amount=parseFloat(repayment_amount)+parseFloat(amount);
            break;
        case "Maintenance_Fee":
        case "Admin_Fee_Sub_Sale":
        case "Admin_Fee_Owner_Ship":
        case "Admin_Fee_Reschdule":
        case "Admin_Fee_Change_Unit":
        case "Tittle_Transfer_Fee":
        case "Stamp_Tax_Fee":
        case "Water_Fee":
        case "Rental_Fee":
        case "Entrance_Card_Fee":
        case "Internet_Service_Fee":
        case "CCTV_Fee":
        case "Renovation_Fee":
          sub_total= parseFloat(sub_total)+parseFloat(amount);
          break;
        default:
          total_installment = 0;
      }
  });
  if(total_penalty>0){
    $(".act_penalty").removeClass( "hiden" );
    $(".act_penalty_blog_deit").val(total_penalty);
    $(".act_penalty_blog_credit").val(total_penalty);
    $("#sch_total").val(total_penalty);
    $("#act_penalty").val(total_penalty);
    $("#act_total").val(total_penalty);
    $("#total-penalty").val(total_penalty);   

    $("#repayment_amount").val(repayment_amount);
  }
  document.getElementById("res-total-deposit").innerHTML=cound_deposit;
  document.getElementById("item-deposit-list").innerHTML=item_deposit_list;

  document.getElementById("Total_Payable").innerHTML='USD ' + total; 
  document.getElementById("res-grand-total").innerHTML='$ ' + total; 
  document.getElementById("result-grand-total").value=total;   
  document.getElementById("total_amount").value='$ ' + total.toFixed(2); 
  document.getElementById("sub_total").value=sub_total.toFixed(2);
  
  
}

function  getJournalDetail(params = array(),entry_date=null)
{
    var output = '<table class="journal-helper tb-search-box">';
    if (entry_date) {
        output += '<tr>';
        output += '<td>';
        output += '<span>Entry Date</span>';
        output += '<input type="text" name="entry_date[]" class="form-control" readonly="readonly" value="' + entry_date+ '" />';
        output += '<input type="hidden" name="branch_code[]" value="' + params.data.branch_code + '" />';
        output += '</td>';

        output += '<td>';
        output += '<span>Invoice Number</span>';
        output += '<input type="text" name="invoice_number[]" class="form-control" />';
        output += '</td>';

        output += '<td>';
        output += '<span>Branch</span>';
        output += '<input type="text" name="branch" class="form-control" readonly="readonly" value="' + params.data.branch_name + '" />';
        output += '<input type="hidden" name="branch_id[]" value="' + params.data.loan.company_branch_id + '" />';
        output += '</td>';

        output += '<td>';
        output += '<span>Currency</span>';
        output += '<input type="text" name="currency_label" class="form-control" readonly="readonly" value="' + getCurrency(params.data.currency) + '" />';
        output += '<input type="hidden" name="currency[]" value="' + params.data.currency + '" />';
        output += '</td>';

        output += '<td></td>';
        output += '</tr>';
    }

    output += '<tr>';
    output += '<td colspan="2" class="h-parent_debit" width="600">';
    output += '<span>Debit</span>';
    output += '<input type="text" name="parent_debit_label" class="parent_debit_label_className form-control" readonly="readonly" value="' + params.data.coa_1.name + '" />';
    output += '<input type="hidden" class="parent_debit_className" name="parent_debit[]" value="' + params.data.coa_1.id + '" />';
    output += '</td>';

    if (params.data.loan.contract_id) {
        output += '<td>';
        output += '<span>Contract ID</span>';
        output += '<input type="text" name="contract_id[]" class="form-control" readonly="readonly" value="' + params.data.loan.contract_id + '" />';
        output += '</td>';
    }
    output += '<td>';
    output += '<span>Debit</span>';
    output += '<input type="text" name="debit[]" class="form-control parent_debit_className_value  " readonly="readonly" value="' + params.data.coa_1.name + '" />';
    output += '</td>';

    output += '<td>';
    output += '<span>Description</span>';
    output += '<input type="text" name="d_description[]" class="form-control"  />';
    output += '</td>';

    output += '</tr>';

    output += '<tr>';
    output += '<td colspan="2" class="h-parent_credit">';
    output += '<span>Credit</span>';
    output += '<input type="text" name="parent_credit_label" class="form-control parent_credit_label_className" readonly="readonly" value="' + params.data.coa_2.name + '" />';
    output += '<input type="hidden" class="parent_credit_className" name="parent_credit[]" value="' + params.data.coa_2.id + '" />';
    output += '</td>';

    if (params.data.loan.contract_id) {
        output += '<td>';
        output += '<span>Contract ID</span>';
        output += '<input type="text" name="credit_contract_id" class="form-control" readonly="readonly" value="' + params.data.loan.contract_id + '" />';
        output += '</td>';
    }

    output += '<td>';
    output += '<span>Credit</span>';
    output += '<input type="text" name="credit[]" class="form-control parent_credit_className_value" readonly="readonly" value="' + params.data.coa_2.id + '" />';
    output += '</td>';

    output += '<td>';
    output += '<span>Description</span>';
    output += '<input type="text" name="c_description[]" class="form-control"  />';
    output += '</td>';
    output += '</tr>';

    output += '<tr>';
    output += '<td colspan="5">';
    output += '<span>Description</span>';
    output += '<textarea name="description[]" class="form-control"></textarea>';
    output += '</td>';
    output += '</tr>';
    output += '</table>';
    document.getElementById("journal-blog").innerHTML=output;
}
function getCurrency(currency){
  switch(currency){
    case 1:
      return 'KHR';
    case 2:
      return 'USD';
    case 3:
      return 'EUR';
    case 4:
      return 'JPY';
    case 5:
      return 'THB';
    case 6:
      return 'HKD';
    case 7:
      return 'MYR';
    case 8:
      return 'SGD';
    case 9:
      return 'VND';
    default:
      return null;
  }
}
