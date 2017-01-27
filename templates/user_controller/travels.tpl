<h1>Travels</h1>

<ul id="travellist">
    {loop $travels}
    <li><a href="/travel/edit/{$travel_id}" target="_self">
            <strong>{$name}</strong> - {if $startdate}{$startdate} - {$enddate}{/if}
            <br/>{$description}</a></li>
    {/loop}
</ul>