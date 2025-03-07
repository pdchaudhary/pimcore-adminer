pimcore.registerNS("pimcore.plugin.Pim.plugin");

pimcore.plugin.Pim.plugin = Class.create({
    checkForUpdateElements: {},
    lastHistoryItem: null,
    historyItems: [],
    historyIndex: null,

    getClassName: function () {
        return "pimcore.plugin.Pim.plugin";
    },

    initialize: function () {
        if(typeof pimcore.events !== 'undefined') {
            Ext.Object.each(pimcore.events, function(eventName) {
                document.addEventListener(pimcore.events[eventName], (e) => {
                    if(typeof this[eventName] === 'function') {
                        this[eventName].apply(this, Object.values(e.detail));
                    }
                });
            }.bind(this));
        } else {
            pimcore.plugin.broker.registerPlugin(this);
        }
    },

    uninstall: function () {
        //TODO remove from menu
    },

    pimcoreReady: function () {
        var user = pimcore.globalmanager.get("user");
        if (user.admin) {
            var extrasMenu = pimcore.globalmanager.get("layout_toolbar").extrasMenu;
            if (extrasMenu) {
                var systemMenu = extrasMenu.queryById('pimcore_menu_extras_system_info');
                if (!systemMenu) {
                    Ext.each(extrasMenu.items.items, function (extraMenuItem) {
                        if (extraMenuItem.text === t("system_infos_and_tools")) {
                            systemMenu = extraMenuItem;
                            return false;
                        }
                    });
                }

                if (systemMenu) {
                    var adminerMenuItem = null;
                    var systemMenuItems = systemMenu.menu.items.items;
                    Ext.each(systemMenuItems, function (systemMenuItem) {
                        if (systemMenuItem.itemId === 'pimcore_menu_extras_system_info_database_administration' || systemMenuItem.text === t("database_administration")) {
                            adminerMenuItem = systemMenuItem;
                            return false;
                        }
                    });
                    if (adminerMenuItem === null) {
                        systemMenu.menu.add({
                            text: t("database_administration"),
                            iconCls: "pimcore_nav_icon_mysql",
                            handler: function () {
                                pimcore.helpers.openGenericIframeWindow(
                                    "adminer",
                                    "/admin/CORSAdminerBundle/adminer",
                                    "pimcore_icon_mysql",
                                    "Database Admin"
                                );
                            }
                        });
                    } else {
                        adminerMenuItem.setHandler(function () {
                            pimcore.helpers.openGenericIframeWindow(
                                "adminer",
                                "/admin/CORSAdminerBundle/adminer",
                                "pimcore_icon_mysql",
                                "Database Admin"
                            );
                        });
                    }
                }
            }
        }
    },
});

new pimcore.plugin.Pim.plugin();
