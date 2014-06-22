#!/bin/bash
curl -XPOST -s -H "Authorization: token $1" -H "Content-Type: application/json" --data \{\"tag_name\":\"$2\",\"target_commitish\":\"$3\"\} https://api.github.com/repos/manuelkiessling/soundvenirs-backend/releases
