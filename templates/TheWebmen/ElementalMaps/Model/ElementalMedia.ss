<% if $MediaType == 'image' %>
    <p>
        $MediaImage
    </p>
<% else_if $MediaType == 'video' %>
    <div class="embed-responsive embed-responsive-<% if $VideoRatio %>4x3<% else %>16x9<% end_if %>">
        <iframe src="{$VideoEmbedURL}" class="no-margin"></iframe>
    </div>
<% end_if %>
