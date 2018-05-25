<% loop $Me %>
    <% if $SecurityAlerts %>
    <div class="message bad">
        <strong>$Name</strong>
        <dl>
            <% loop $SecurityAlerts %>
            <dt><a href="$ExternalLink">$CVE</a></dt>
            <dd>$Title</dd>
            <% end_loop %>
        </dl>
    </div>
    <% end_if %>
<% end_loop %>
