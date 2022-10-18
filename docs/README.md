# Local Jekyll Set Up

## Set Up on WSL (Ubuntu 18.04)

    sudo apt-get update
    sudo apt-get install ruby ruby-dev make build-essential
    sudo apt install zlibc zlib1g-dev libxml2 libxml2-dev libxslt1.1 libxslt1-dev
    sudo gem update --system
    sudo gem install jekyll bundler
    bundle install

To run

    bundle exec jekyll serve --baseurl '' --port 8080

To include drafts

    bundle exec jekyll serve --drafts --baseurl '' --port 8080

