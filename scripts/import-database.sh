# import db from docker container
docker exec -i database mysql -usitename -psitename sitename < ../dump.sql