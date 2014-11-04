pimcore.registerNS("pimcore.plugin.userPlugin");

pimcore.plugin.userPlugin = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return "pimcore.plugin.userPlugin";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);

        // additional menu for users administration
        this.navEl = Ext.get('pimcore_menu_logout').insertSibling('<li id="pimcore_menu_users" class="pimcore_menu_item icon-user">'+t('Users')+'</li>');
    },

    pimcoreReady: function (params, broker) {
        var menu = new Ext.menu.Menu({
            items: [
                {
                    text: t("Users"),
                    iconCls: "pimcore_icon_apply",
                    handler: this.showTab
                },
                {
                    text: t("Unvalidated"),
                    iconCls: "pimcore_icon_delete",
                    handler: function () {alert("pressed 2")}
                }],
            cls: "pimcore_navigation_flyout"
        });

        var toolbar = pimcore.globalmanager.get("layout_toolbar");
        this.navEl.on("mousedown", toolbar.showSubMenu.bind(menu));
        // this.navEl.on("mousedown", this.showTab);
    },
    
    
    showTab: function() {
        userPlugin.panel = new Ext.Panel({
            id:         "user_panel",
            title:      t("Users"),
            iconCls:    "user_menu_icon",
            border:     false,
            layout:     "fit",
            closable:   true,
            items:      [
                    userPlugin.getGrid()
            ]
        });

        var tabPanel = Ext.getCmp("pimcore_panel_tabs");
        tabPanel.add(userPlugin.panel);
        tabPanel.activate("user_panel");
        pimcore.layout.refresh();
    },

    getGrid: function() {
        userPlugin.store = new Ext.data.JsonStore({
            id: 'users_store',
            url: '/plugin/User/store/get-users',
            root: 'users',
            totalProperty: 'total',
            idProperty: 'o_id',
            remoteSort: 'true',
            fields: [
                'o_id',
                'name',
                'surname',
                'email'
            ]
        });

        userPlugin.store.load();

        var typeColumns = [
            {header: t("O_ID"),				sortable: true, dataIndex: 'o_id'},
            {header: t("NAME"), 		    sortable: true, dataIndex: 'name'},
            {header: t("SURNAME"),			sortable: true, dataIndex: 'surname'},
            {header: t("EMAIL"),			sortable: true,	dataIndex: 'email'},
        ];

        userPlugin.grid = new Ext.grid.GridPanel({
            frame: false,
            autoScroll: true,
            store: userPlugin.store,
            columns: typeColumns,
            trackMouseOver:	true,
            columnLines: true,
            stripeRows: true,
            // paging bar on the bottom
            bbar: new Ext.PagingToolbar({
                pageSize: 35,
                store: userPlugin.store,
                displayInfo: true,
                displayMsg: '{0} - {1} / {2}'
            })
        });

        return userPlugin.grid;
    }

});

var userPlugin = new pimcore.plugin.userPlugin();


/*


function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre)
}

pimcore.registerNS("pimcore.plugin.billingPlugin");

pimcore.plugin.billingPlugin = Class.create(pimcore.plugin.admin, {
	
    getClassName: function() {
        return "pimcore.plugin.billingPlugin";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },
 
 
 
    pimcoreReady: function (params,broker){
        // add a sub-menu item under "Extras" in the main menu
        var toolbar = Ext.getCmp("pimcore_panel_toolbar");

        var action = new Ext.Action({
            id: "billing_menu_item",
            text: "Orders",
            iconCls:"billing_menu_icon",
            handler: this.showTab
        });

        toolbar.items.items[1].menu.add(action);
    },

    showTab: function() {
        billingPlugin.panel = new Ext.Panel({
            id:         "billing_panel",
            title:      "Orders",
            iconCls:    "billing_menu_icon",
            border:     false,
            layout:     "fit",
            closable:   true,
            items:      [
                         	billingPlugin.getGrid()
            ]
        });

        var tabPanel = Ext.getCmp("pimcore_panel_tabs");
        tabPanel.add(billingPlugin.panel);
        tabPanel.activate("billing_panel");

        pimcore.layout.refresh();
    },
    
    getGrid: function() {
    	billingPlugin.store = new Ext.data.JsonStore({
    		id:			'orders_store',
    		url:		'/plugin/Billing/store/get-orders',
    		root:		"orders",
            totalProperty: 'total',
            idProperty: 'id',
            remoteSort: true,
    		
    		fields: [
    		         "id",
    		         "priceTotal",
    		         "name",
    		         "surname",
    		         "paid",
    		         "entity"
    		]
    	});
    	
    	
    	
    	billingPlugin.store.load();
    	
    	var typeColumns = [
    			{header: t("ID"),		sortable: true, dataIndex: 'id'},
    			{header: t("NAME"),		sortable: true, dataIndex: 'name'},
    			{header: t("SURNAME"),	sortable: true,	dataIndex: 'surname'},
    			{header: t("AMOUNT"),	sortable: true, dataIndex: 'priceTotal'},
    			{header: t("ENTITY"),	sortable: true, dataIndex: 'entity'},
    			{header: t("PAID"),		sortable: true, dataIndex: 'paid'}
    	];
    	
    	billingPlugin.menuUnpaid = new Ext.menu.Menu({
    		items: [{
    			id:			'confirm',
    			text:		'CONFIRM',
    			iconCls:	'confirm_icon'
    		}],
    		listeners: {
    			itemclick: function(item) {
    				switch(item.id) {
    					case 'confirm':
    						
    						billingPlugin.menuUnpaid.hide();
    						
    						Ext.Ajax.request({
    							url: '/plugin/Billing/payment/confirm',
    							params: { id: billingPlugin.record.get('id') },
    							method: 'GET',
    							success: function(result, request) {
    								var obj = eval("(" + result.responseText + ')');
    								if(obj.success) {    									
    									billingPlugin.record.set('paid', obj.date);
    								} else {
    									alert("ERROR updateing order");
    								}
    							}
    						});
    						
    						break;
    				} 
    			}
    		}
    	});
    	

    	
    	billingPlugin.menuPaid = new Ext.menu.Menu({
    		items: [{
    			id:			'details'
    			,text:		'DETAILS'
    			,iconCls:	'details_icon'
    		}],
    		listeners: {
    			itemclick: function(item) {
    				switch(item.id) {
    					case 'details':
    				} 
    			}
    		}
    	});
    	
    	
    	
    	billingPlugin.grid = new Ext.grid.GridPanel({
    		frame:			false,
    		autoScroll: 	true,
    		store:			billingPlugin.store,
    		columns:		typeColumns,
    		trackMouseOver:	true,
            columnLines:    true,
            stripeRows:     true,
            viewConfig:     { 
            	forceFit: true,
            	autoFill: true,
            	getRowClass: function(record, index) {
            		if(record.get('paid'))
            			return 'record_paid';
            	}
            },
            listeners:		{
            	'rowcontextmenu' : function(grid, index, event) {
            		event.stopEvent();
      		        billingPlugin.record = grid.getStore().getAt(index);
      		        billingPlugin.gridIndex = index;
      		        
      		        if(billingPlugin.record.get('paid') == null) {
      		        	billingPlugin.menuUnpaid.showAt(event.xy);
      		        }
      		        
            	 },
            	 
            	 'rowdblclick' : function(grid, index, event) {
            		billingPlugin.record = grid.getStore().getAt(index);
					if(billingPlugin.record.get('paid') != null) {
						
						
						
						Ext.Ajax.request({
							url: '/plugin/Billing/payment/order-details',
							params: { id: billingPlugin.record.get('id') },
							method: 'GET',
							success: function(result, request) {
								console.log(result.responseText);
								
						    	billingPlugin.infoWin = new Ext.Window({

							        
					                title: 'ORDER_DETAIL_INFO_WIN',
					                closable: true,
					                closeAction: 'hide',
					                //animateTarget: this,
					                width: 600,
					                height: 600,
					                layout: 'border',
					                bodyStyle: 'padding: 5px;',
					                items: [{
					                		region: 'center',
					                        title: t('ORDER'),
					                        html: result.responseText
					                }],
					                buttons : [{
			                               text    : 'PDF',
			                               handler : function(button, event) {
			                            	    // get asset id
				           						Ext.Ajax.request({
				        							url: '/plugin/Billing/payment/get-bill',
				        							params: { id: billingPlugin.record.get('id') },
				        							method: 'GET',
				        							success: function(result, request) {
				        								var obj = eval("(" + result.responseText + ')');
				        								if(obj.success) { 
				        									pimcore.helpers.openAsset(obj.asset, "document");
				        								} else {
				        									alert("ERROR opening bill");
				        								}
				        							}
				        						});			                            	   
			                               }
					                }]					                							        
						    	}).show();								
							}
						});
						
			              
					}
            	 }
            },
            
            // paging bar on the bottom
            bbar: new Ext.PagingToolbar({
                pageSize: 35,                
                store: billingPlugin.store,
                displayInfo: true,
                displayMsg: '{0} - {1} / {2}'
            })
            
            
    	});
    	
    	return billingPlugin.grid;
    },
      
    
});
var billingPlugin = new pimcore.plugin.billingPlugin();


pimcore.registerNS("pimcore.layout.portlets.billingPortlet");
pimcore.layout.portlets.billingPortlet = Class.create(pimcore.layout.portlets.abstract, {

    getType: function () {
        return "pimcore.layout.portlets.billingPortlet";
    },


    getName: function () {
        return t("BILLING_PORTLET");
    },

    getIcon: function () {
        return "billing_menu_icon";
    },
    
    getLayout: function () {
    	
    	store = new Ext.data.JsonStore({
        		id:			'orders_store',
        		url:		'/plugin/Billing/store/get-orders/per/15',
        		root:		"orders",
                totalProperty: 'total',
                idProperty: 'id',
                remoteSort: true,
        		
        		fields: [
        		         "id",
        		         "priceTotal",
        		         "name",
        		         "surname",
        		         "paid",
        		]
        	});
    	store.load();
        	
    	var typeColumns = [
    			{header: t("ID"),		sortable: true, dataIndex: 'id'},
    			{header: t("NAME"),		sortable: true, dataIndex: 'name'},
    			{header: t("SURNAME"),	sortable: true,	dataIndex: 'surname'},
    			{header: t("AMOUNT"),	sortable: true, dataIndex: 'priceTotal'},
    			{header: t("PAID"),		sortable: true, dataIndex: 'paid'}
    	];
    	
    	grid = new Ext.grid.GridPanel({
    		frame:			false,
    		autoScroll: 	true,
    		store:			store,
    		columns:		typeColumns,
    		trackMouseOver:	true,
            columnLines:    true,
            stripeRows:     true,
            height: 		275,
            viewConfig:     { 
            	forceFit: true,
            	autoFill: true,
            	getRowClass: function(record, index) {
            		if(record.get('paid'))
            			return 'record_paid';
            	}
            },
            bbar: new Ext.PagingToolbar({
                pageSize: 15,                
                store: billingPlugin.store,
                displayInfo: true,
                displayMsg: '{0} - {1} / {2}'
            })            
       	});

        this.layout = new Ext.ux.Portlet(Object.extend(this.getDefaultConfig(), {
            title: this.getName(),
            iconCls: this.getIcon(),
            height: 300,
            items: [
                   grid
            ]
        }));

        return this.layout;
    } 
    
    

});

*/