"""Check a pre-uploaded NONSECRET private-folder test set. No writes or secrets.

Run only after the server operator has placed the named markers and verified
that they exist via the authorized file-transfer path. A missing file's 404 is
not proof of HTTP denial; every request must return 403 without redirect.
"""
import json
import urllib.error
import urllib.request

ORIGIN = "https://inbes.jp"
PATHS = (
    "/.inbes-private/policy-check.txt",
    "/.inbes-private/policy-check.php",
    "/.inbes-private/policy-check.json",
    "/.inbes-private/policy-check.bak",
    "/.inbes-private/probe-nested/policy-check.txt",
)
METHODS = ("GET", "HEAD", "POST", "OPTIONS")


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


def inspect(opener):
    controls = []
    for path in ("/", "/contact/send.php"):
        url = ORIGIN + path
        status, healthy = None, False
        try:
            request = urllib.request.Request(url, headers={"Cache-Control": "no-store", "User-Agent": "Mozilla/5.0"})
            with opener.open(request, timeout=10) as response:
                status = response.status
                healthy = status == 200 and response.geturl() == url
                if healthy and path.endswith("send.php"):
                    body = response.read(65537)
                    data = json.loads(body) if len(body) <= 65536 else None
                    healthy = isinstance(data, dict) and data.get("ok") is True
        except urllib.error.HTTPError as error:
            status = error.code
            error.close()
        except Exception:
            healthy = False
        controls.append({"path": path, "status": status, "healthy": healthy})
    outcomes = []
    for path in PATHS:
        for method in METHODS:
            url = ORIGIN + path
            req = urllib.request.Request(url, method=method,
                    headers={"Cache-Control": "no-store", "User-Agent": "Mozilla/5.0"})
            status, denied = None, False
            try:
                with opener.open(req, timeout=10) as response:
                    status = response.status
                    denied = status == 403 and response.geturl() == url
            except urllib.error.HTTPError as error:
                status = error.code
                denied = status == 403 and error.geturl() == url
                error.close()
            except Exception:
                pass
            outcomes.append({"path": path, "method": method,
                             "status": status, "denied": denied})
    return {"http_denial_verified": all(row["healthy"] for row in controls) and all(row["denied"] for row in outcomes),
            "public_controls": controls,
            "requests": outcomes, "secrets_read_or_written": False}


if __name__ == "__main__":
    result = inspect(urllib.request.build_opener(NoRedirect()))
    print(json.dumps(result))
    raise SystemExit(0 if result["http_denial_verified"] else 1)
