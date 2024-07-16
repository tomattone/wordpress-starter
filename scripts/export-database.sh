# dump db from docker container
docker exec -i database mysqldump -usitename -psitename sitename > ../dump.sql