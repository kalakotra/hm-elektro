<div class="form-section<% if $ShowContactColumn %> form-section--split<% end_if %>">
    <div class="row g-4 g-lg-5">
        <div class="<% if $ShowContactColumn %>col-lg-5<% else %>col-lg-9 col-xl-8<% end_if %> form-section__head">
            <% if $Eyebrow %><p class="eyebrow">$Eyebrow</p><% end_if %>
            <% if $Title && $ShowTitle %><h2 class="form-section__title">$Title</h2><% end_if %>
            <% if $Intro %><p class="form-section__intro">$Intro</p><% end_if %>
            <% if $ShowContactColumn %><% with $SiteConfig %><% include ContactCards %><% end_with %><% end_if %>
        </div>
        <div class="<% if $ShowContactColumn %>col-lg-7<% else %>col-lg-9 col-xl-8<% end_if %>">
            <div class="form-card">
                $Form
            </div>
        </div>
    </div>
</div>
