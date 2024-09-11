FROM nginx

ADD deploy/config/dev/nginx/nginx.conf /etc/nginx/conf.d/default.conf
