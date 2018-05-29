(function($) {
  $.entwine('ss', function($) {
    $('.package-summary__security-alerts').entwine({
      IsShown: false,
      toggleSecurityNotices: function() {
        if (this.getIsShown()) {
          this.hideSecurityNotices();
        } else {
          this.showSecurityNotices();
        }
      },
      showSecurityNotices: function() {
        this.getAlertList().show();
        this.setIsShown(true);
      },
      hideSecurityNotices: function() {
        this.getAlertList().hide();
        this.setIsShown(false);
      },
      getAlertList: function() {
        return this.children('.security-alerts__list');
      }
    });
    $('.security-alerts__toggler').entwine({
      onclick: function(event) {
        this.parent()
          .nextAll('.package-summary__security-alerts')
          .toggleSecurityNotices();
        event.preventDefault();
      }
    });
  });
})(jQuery)
