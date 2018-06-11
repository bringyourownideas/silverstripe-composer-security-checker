<p class="site-summary__security-alerts">
    <strong><%t SecurityAlertSummary.TITLE "Security alert" %></strong><br />
    <% if $Count > 1 %>
        <%t SecurityAlertSummary.NOTICE_MANY "Notices have been issued for <strong>{count}</strong> of your modules. Review and updating is recommended." count=$Count %>
    <% else %>
        <%t SecurityAlertSummary.NOTICE_ONE "A notice has been issued for <strong>{count}</strong> of your modules. Review and updating is recommended." count=$Count %>
    <% end_if %>
</p>