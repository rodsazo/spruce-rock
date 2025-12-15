
jQuery( function ($){
    const observer = new IntersectionObserver(
        function(entries, observer){
            entries.forEach((entry, index) => {
                if( entry.isIntersecting ) {
                    entry.target.style.setProperty('--intersectDelay', (index * 0.2) + 's')
                    entry.target.classList.add('intersected');
                }
            });
        }, {
            threshold: 0.25
        });

    $('.intersect').each(function(i,el){
        observer.observe( el );
    });
});