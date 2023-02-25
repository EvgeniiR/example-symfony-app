FROM nginx:1.23.3
COPY nginx.conf /etc/nginx/nginx.conf
ADD sites-enabled /etc/nginx/sites-enabled
WORKDIR /app
EXPOSE 80
EXPOSE 443
CMD ["nginx"]
