FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN echo 'tzdata tzdata/Areas select Asia' | debconf-set-selections && \
echo 'tzdata tzdata/Zones/Asia select Colombo' | debconf-set-selections

# clone git repo
RUN apt-get update && apt-get install -y git && apt-get install curl -y && apt install php libapache2-mod-php -y && \
    apt-get install php-curl php-xml php-zip php-mysql -y

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH=/root/.nvm/versions/node/v22.2.0/bin:$PATH

SHELL ["/bin/bash", "-c"]
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash \
    && export NVM_DIR="$HOME/.nvm" \
    && . "$NVM_DIR/nvm.sh" \
    && nvm install 22.2.0 \
    && nvm use 22.2.0 \
    && echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.bashrc \
    # && echo $NVM_DIR \
    && echo '[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"' >> ~/.bashrc \
    && source ~/.bashrc 

# RUN export NVM_DIR="$HOME/.nvm" \
#     && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

# Add NVM to PATH
# ENV NODE_VERSION=$(nvm version --no-aliases | awk -F'/' '{ print $2 }')

# SHELL ["/bin/bash", "-c"]
# RUN echo $NVM_DIR
# RUN echo $PATH

# RUN nvm --version

RUN git clone https://github.com/humtravel/laravel-inertia-todo-app.git

WORKDIR /laravel-inertia-todo-app

RUN npm install && composer update && composer install && mv .env.example .env && npm run build && php artisan key:generate
RUN sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql-db/' .env && sed -i 's/DB_PASSWORD=/DB_PASSWORD=pwd2/' .env

COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

EXPOSE 8000

# RUN php artisan migrate && php artisan db:seed

CMD ["/bin/bash", "-c", "/usr/local/bin/wait-for-it.sh mysql-db:3306 -- \
    php artisan migrate:fresh && php artisan db:seed && \
    php artisan serve --host=0.0.0.0 --port=8000"]
