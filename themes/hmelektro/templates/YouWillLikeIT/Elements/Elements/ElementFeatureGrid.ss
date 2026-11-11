<div class="feature-grid feature-grid--$Variant">
    <% if $Heading || $Intro %>
    <header class="section-head section-head--$HeaderAlign">
        <div class="section-head__text">
            <% if $Eyebrow %><p class="eyebrow">$Eyebrow</p><% end_if %>
            $Heading('section-head__title')
            <% if $Intro %><p class="section-head__intro">$Intro</p><% end_if %>
        </div>
        <% if $HeaderAlign == 'split' %><% include CtaButtons Tone=$ButtonTone, Class='section-head__actions' %><% end_if %>
    </header>
    <% end_if %>
    <% if $SortedItems.exists %>
        <% if $Variant == 'steps' %>
        <ol class="row g-4 g-lg-5 list-unstyled mb-0 steps $ColumnClass">
            <% loop $SortedItems %>
            <li class="col step">
                <span class="step__no" aria-hidden="true">$Pos</span>
                <$Up.ChildHeadingTag class="step__title">$Title</$Up.ChildHeadingTag>
                <% if $Text %><p class="step__text">$Text</p><% end_if %>
            </li>
            <% end_loop %>
        </ol>
        <% else_if $Variant == 'columns-rule' %>
        <ul class="row g-4 g-lg-5 list-unstyled mb-0 $ColumnClass">
            <% loop $SortedItems %>
            <li class="col">
                <div class="rule-item">
                    <$Up.ChildHeadingTag class="rule-item__title">$Title</$Up.ChildHeadingTag>
                    <% if $Text %><p class="rule-item__text">$Text</p><% end_if %>
                </div>
            </li>
            <% end_loop %>
        </ul>
        <% else_if $Variant == 'icon-list' %>
        <ul class="row g-4 g-lg-5 list-unstyled mb-0 $ColumnClass">
            <% loop $SortedItems %>
            <li class="col">
                <div class="icon-item">
                    <% if $Icon %><span class="icon-item__icon"><% include Icon Name=$Icon %></span><% end_if %>
                    <div>
                        <$Up.ChildHeadingTag class="icon-item__title"><% if $Link.exists %><a href="$Link.URL"<% if $Link.OpenInNew %> target="_blank" rel="noopener"<% end_if %>>$Title</a><% else %>$Title<% end_if %></$Up.ChildHeadingTag>
                        <% if $Text %><p class="icon-item__text">$Text</p><% end_if %>
                    </div>
                </div>
            </li>
            <% end_loop %>
        </ul>
        <% else %>
        <ul class="row g-4 list-unstyled mb-0 $ColumnClass">
            <% loop $SortedItems %>
            <li class="col">
                <article class="card service-card h-100<% if $Link.exists %> service-card--linked<% end_if %>">
                    <% if $Image.exists %>
                        <% with $Image.FillMax(800,500) %>
                        <img class="card-img-top" src="$URL" width="$Width" height="$Height" alt="$Up.ImageAltText.ATT" loading="lazy" decoding="async">
                        <% end_with %>
                    <% end_if %>
                    <div class="card-body d-flex flex-column">
                        <div class="service-card__top">
                            <% if $Icon %><span class="service-card__icon"><% include Icon Name=$Icon %></span><% end_if %>
                            <span class="service-card__no" aria-hidden="true">$NumberLabel</span>
                        </div>
                        <$Up.ChildHeadingTag class="service-card__title"><% if $Link.exists %><a class="stretched-link" href="$Link.URL"<% if $Link.OpenInNew %> target="_blank" rel="noopener"<% end_if %>>$Title</a><% else %>$Title<% end_if %></$Up.ChildHeadingTag>
                        <% if $Text %><p class="service-card__text">$Text</p><% end_if %>
                        <% if $Link.exists %><span class="service-card__more" aria-hidden="true">$Link.Title <% include Icon Name='arrow', Class='icon-sm' %></span><% end_if %>
                    </div>
                </article>
            </li>
            <% end_loop %>
        </ul>
        <% end_if %>
    <% end_if %>
    <% if $HeaderAlign != 'split' %><% include CtaButtons Tone=$ButtonTone, Class='mt-5' %><% end_if %>
</div>
