<%-- One service page as numbered card (scope: ServicePage). Heading level h3 (cards sit under an h2). --%>
<article class="card service-card service-card--linked h-100">
    <div class="card-body d-flex flex-column">
        <div class="service-card__top">
            <% if $Icon %><span class="service-card__icon"><% include Icon Name=$Icon %></span><% end_if %>
            <span class="service-card__no" aria-hidden="true">$NumberLabel</span>
        </div>
        <h3 class="service-card__title"><a class="stretched-link" href="$Link">$MenuTitle</a></h3>
        <% if $Teaser %><p class="service-card__text">$Teaser</p><% end_if %>
        <span class="service-card__more" aria-hidden="true"><%t App.LearnMore 'Mehr erfahren' %> <% include Icon Name='arrow', Class='icon-sm' %></span>
    </div>
</article>
