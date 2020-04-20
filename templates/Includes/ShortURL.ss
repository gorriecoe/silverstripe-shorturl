<div class="admin-shorturl">
    <% if AbsoluteLink %>
        <a href="{$AbsoluteLink}" target="_blank">
            {$AbsoluteLink}
        </a>
        <% if LinkURL %>
            <%t TO 'to' %>
            <a href="{$LinkURL}" target="_blank">
                {$LinkURL}
            </a>
        <% end_if %>
    <% else %>
        <%t SAVEFIRSTTOGENERATELINK 'Save first to generate link' %>
    <% end_if %>
</div>
