<% if $SecurityAlerts %>
<div class="package-summary__security-alerts">
    <dl class="security-alerts__list">
        <% loop $SecurityAlerts %>
        <dt><a href="$ExternalLink">$Identifier</a></dt>
        <dd>$Title</dd>
        <% end_loop %>
    </dl>
</div>
<% end_if %>
