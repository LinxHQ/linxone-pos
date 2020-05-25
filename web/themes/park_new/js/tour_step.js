// Instance the tour
var data_demo = new Tour({
    backdrop:true,
    backdropContainer: 'body',
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'/clubms_2017/index.php/report/default/receivable',
            data:{},
            success:function(data){
                
            }
        });
    },
    steps: [
        {
            element: ".parkclub-search",
            title: "Title of my step",
            content: "<p>In Checkin/Checkout tab, members can check in by their member cards.</p>In case, they forgot their cards, receptionist can help them check in/out by their ID number, mobile phone or member barcode.",
            placement:"left",
            onShow: function(){
                $('#txt_card_id').val('MS20171');
            }
        },
        {
           element: ".parkclub-checkbtn",
           title: "Title of my step",
           content: "Press “Checkin/out”button or “Enter” to show up member detailed information.",
           placement:"left",
           backdrop:false,
            onNext: function(){
                popcheckin();
                data_demo.end();
            },
         },
        {
           element: "#pop_checktour",
           title: "Title of my step",
           content: "Press “Checkin/out”button or “Enter” to finish the process.",
            onNext: function(){
                $('#pop_checktour').click();
            },
//            onPrev: function(){
//                hideModel('bs-model-checkin');
//            }
         },
        {
           element: ".receivable",
           title: "Title of my step",
           content: "In Account Receivable, tracking and checking your business become so easy.",
            onNext: function(){
                document.location.href = '/clubms_2017/index.php/report/default/receivable';
            },
         },
        {
           element: ".paymentreport",
           title: "Title of my step",
           content: "In Payement Report, you can have an overview about financial status of your business.",
            onNext: function(){
                document.location.href = '/clubms_2017/index.php/report/default/paymentreport';
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         },
    ],
    template:'<div class="popover" role="tooltip"><div class="arrow"></div> <h3 class="popover-title"></h3> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next">Next &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end">End tour</button> </div> </div>'
});

// Instance the tour
var tour_no_demo = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'/clubms_2017/index.php/endtour',
            data:{},
            success:function(data){
                
            }
        });
    },
    steps: [
        {
            element: ".membership_type",
            title: "Title of my step",
            content: "In order to add new membership, please click here. Press, Next to continue. ",
            onNext: function(){
                document.location.href = '/clubms_2017/index.php/membership_type/default/index';
            }
        },
        {
           element: "#tour-add-membership-type",
           title: "Title of my step",
           content: "Please click here to create new membership type.",
           placement:"left",
            onNext: function(){
                tour_no_demo.ended();
                $('#tour-add-membership-type').click();
            },
         },
        {
           element: "#form_membership_type",
           title: "Title of my step",
           content: "Fill in membership information.",
           placement:"left",
         }, 
        {
           element: "#tour-create-membership-type",
           title: "Title of my step",
           content: 'After filling in membership information, click on "Create" button to save changes.',
            onNext: function(){
                $('#tour-create-membership-type').click();
            },
//            onPrev: function(){
//                hideModel('bs-model-checkin');
//            }
         },
        {
           element: "#form_membership_type_price",
           title: "Title of my step",
           content: "In order to insert membership price, please fill in price and period field.",
           placement:"left",
         },
        {
           element: "#create-mbshiptype-price",
           title: "Title of my step",
           content: 'After filling in price and period, please click "Create" to save.',
            onNext: function(){
                $('#create-mbshiptype-price').click();
            },
         },
        {
           element: ".members",
           title: "Title of my step",
           content: "Press “Next to continue” and add a guest or a member.",
            onNext: function(){
                document.location.href = '/clubms_2017/index.php/members/default/index?m=members';
            },
         },
        {
           element: "#add-guest",
           title: "Title of my step",
           content: "Click on “Add guest” to create a guest or member account.",
           placement:"left",
            onNext: function(){
                $('#add-guest').click();
            },
         },
         
        {
           element: "#form_membership",
           title: "Title of my step",
           content: "Fill in required information for a guest or a member.",
           placement:"left"
         },
         
        {
           element: "#create-member",
           title: "Title of my step",
           content: 'To start this process, please click on "Add guest" button.',
           placement:"left",
            onNext: function(){
                $('#create-member').click();
            },
         },  
        {
           element: "#new-payment",
           title: "Title of my step",
           content: "Create new payment here.",
            onNext: function(){
                $('#new-payment').click();
            },
         }, 
        {
           element: "#create-invoice",
           title: "Title of my step",
           content: "Press Paid to confirm member payment and print receipt for your customers.",
            onNext: function(){
                tour_no_demo.ended();
                $('#create-invoice').click();
            },
         }, 
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next">Next &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end">End tour</button> </div> </div>'
});

// Instance the tour
var tour_membership_type = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'/clubms_2017/index.php/endtour',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
        {
           element: "#tour-add-membership-type",
           title: "Title of my step",
           content: "Please click here to create new membership type.",
           placement:"left",
            onNext: function(){
                $('#tour-add-membership-type').click();
            },
         },
        {
           element: "#form_membership_type",
           title: "Title of my step",
           content: "Please click here to create new membership type.",
           placement:"left",
         },
        {
           element: "#tour-create-membership-type",
           title: "Title of my step",
           content: 'Click on "Create" button to finish the process.',
            onNext: function(){
                $('#tour-create-membership-type').click();
            },
         },
        {
           element: "#form_membership_type_price",
           title: "Title of my step",
           content: "Please click here to create new membership type.",
           placement:"left",
         },
        {
           element: "#create-mbshiptype-price",
           title: "Title of my step",
           content: 'After filling in price and period, please click "Create" to save.',
            onNext: function(){
                $('#create-mbshiptype-price').click();
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next">Next &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end">End tour</button> </div> </div>'
});
// Instance the tour
var tour_member = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'/clubms_2017/index.php/endtour',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
        {
           element: "#add-guest",
           title: "Title of my step",
           content: "Click on “Add guest” to create a guest or member account.",
           placement:"left",
            onNext: function(){
                $('#add-guest').click();
            },
         },
        {
           element: "#form_membership",
           title: "Title of my step",
           content: "After filling member information, click on “Save” to finish",
           placement:"left"
         },
        {
           element: "#create-member",
           title: "Title of my step",
           content: 'After filling member information, click on “Add Guest”. Guest will become members if you choose memberships. In this case, the system will show up invoice for the payment.',
           placement:"left",
            onNext: function(){
                $('#create-member').click();
            },
         },  
        {
           element: "#new-payment",
           title: "Title of my step",
           content: "Create new payment here.",
            onNext: function(){
                $('#new-payment').click();
            },
         }, 
        {
           element: "#create-invoice",
           title: "Title of my step",
           content: 'Press Paid to confirm member payment and print receipt for your customers.',
            onNext: function(){
                $('#create-invoice').click();
            },
         }, 
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next">Next &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end">End tour</button> </div> </div>'
});

// Instance the tour
var tour_checkout = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'/clubms_2017/index.php/endtour',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
        {
            element: ".parkclub-search",
            title: "Title of my step",
            content: "<p>In Checkin/Checkout tab, members can check in by their member cards.</p>In case, they forgot their cards, receptionist can help them check in/out by their ID number, mobile phone or member barcode.",
            placement:"left",
            onShow: function(){
                $('#txt_card_id').val('MS20171');
            }
        },
        {
           element: ".parkclub-checkbtn",
           title: "Title of my step",
           content: "Press “Checkin/out”button or “Enter” to show up member detailed information.",
           placement:"left",
            onNext: function(){
                popcheckin();
            },
         },
        {
           element: "#pop_checktour",
           title: "Title of my step",
           content: "Press “Checkin/out”button or “Enter” to finish the process.",
           backdrop:false,
            onNext: function(){
                $('#pop_checktour').click();
                
            },
         },
        {
           element: "#pop_checktour_no",
           title: "Title of my step",
           content: "Press “Checkin/out”button or “Enter” to finish the process.",
           backdrop:false,
            onNext: function(){
                $('#pop_checktour').click();
                
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next">Next &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end">End tour</button> </div> </div>'
});