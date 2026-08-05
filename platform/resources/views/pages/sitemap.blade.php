{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc>{{ route('about') }}</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('contact') }}</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <url><loc>{{ route('news.index') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc>{{ route('public-calls.index') }}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('ngos.index') }}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('communities.index') }}</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
    <url><loc>{{ route('events.index') }}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('reports.create') }}</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
    <url><loc>{{ route('register') }}</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
    @foreach($news as $article)
    <url><loc>{{ route('news.show', $article->slug) }}</loc><lastmod>{{ $article->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
    @endforeach
    @foreach($calls as $call)
    <url><loc>{{ route('public-calls.show', $call->slug) }}</loc><lastmod>{{ $call->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
    @endforeach
    @foreach($ngos as $ngo)
    <url><loc>{{ route('ngos.show', $ngo->slug) }}</loc><lastmod>{{ $ngo->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
    @endforeach
    @foreach($communities as $community)
    <url><loc>{{ route('communities.show', $community->slug) }}</loc><lastmod>{{ $community->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
    @endforeach
    @foreach($events as $event)
    <url><loc>{{ route('events.show', $event->slug) }}</loc><lastmod>{{ $event->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
    @endforeach
</urlset>
