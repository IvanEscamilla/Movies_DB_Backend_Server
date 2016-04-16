 var movies;
        var data;
        var movie;
        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };

        for(var i = 0; i<movies.length;i++)
        {
            movie = movies[i];
            data = $.param({
                data: JSON.stringify({
                    movie
                })
            });

            $http.post("http://localhost:120/admin/insert/new_movie",
                data,
                config)
                .then(
                    function (response) {
                        console.log("movie inserted!");
                    },
                    function (response) {
                        console.log("lol!");
                    }
                );
        }