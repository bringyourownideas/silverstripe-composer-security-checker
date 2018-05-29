(function($) {
  $.entwine('ss', function($) {
    $('.package-summary__security-alerts').entwine({
      IsShown: false,
      onclick: function(event) {
        if ($(event.target).is('strong, strong>span')) {
          this.toggleSecurityNotices();
        }
      },
      toggleSecurityNotices: function() {
        if (this.getIsShown()) {
          this.hideSecurityNotices();
        } else {
          this.showSecurityNotices();
        }
      },
      showSecurityNotices: function() {
        this.children('dl').show();
        this.find('strong>span').text('Hide');
        this.setIsShown(true);
      },
      hideSecurityNotices: function() {
        this.children('dl').hide();
        this.find('strong>span').text('Show');
        this.setIsShown(false);
      }
    });
  });
})(jQuery)
