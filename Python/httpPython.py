import httplib2
h = httplib2.Http(".cache")
s = [1,2,3]
(resp_headers, content) = h.request("http://karben14.com/pi2/index.php?data=" + str(s), "GET")
print((resp_headers, content))