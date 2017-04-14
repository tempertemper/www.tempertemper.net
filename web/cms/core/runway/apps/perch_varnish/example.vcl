# Perch sends PURGE requests like:
#      http://example.com/about-us
# and BAN requests like:
#      http://example.com/products/([a-z0-9\-]+)/?$
#
# See https://www.varnish-cache.org/docs/4.0/users-guide/purging.html

acl purge {
    "localhost";
    "192.168.55.0"/24;
}

sub vcl_backend_response {
	set beresp.http.x-url = bereq.url;
}

sub vcl_deliver {
	unset resp.http.x-url; # Optional
}

sub vcl_recv {
    # allow PURGE and BAN from localhost and 192.168.55...

    if (req.method == "PURGE") {
        if (!client.ip ~ purge) {
                return(synth(405,"Not allowed."));
        }
        return (purge);
    }

    if (req.method == "BAN") {
    	if (!client.ip ~ purge) {
    	        return(synth(405,"Not allowed."));
    	}
    	ban("obj.http.x-url ~ " + req.url); # req.url is a regex
    	return(synth(200, "Ban added"));
    }
}
