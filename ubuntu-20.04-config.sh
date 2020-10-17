#!/bin/bash

# Install Ansible
sudo apt update && sudo apt install ansible

# Run the Ansible play-book to install the rest
ansible-playbook ubuntu-20.04-playbook.yml
