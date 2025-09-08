#!/bin/bash
# EC2 User Data Script for AWS 2-Tier Web Application
# This script automatically configures new EC2 instances

# Update system packages
yum update -y

# Install Apache, PHP, and MySQL client
yum install -y httpd php php-mysqli

# Start and enable Apache service
systemctl start httpd
systemctl enable httpd

# Copy application files to web directory
# (Files will be created by this script)

# Set proper permissions
chown -R apache:apache /var/www/html/
chmod -R 755 /var/www/html/

echo "EC2 instance setup completed successfully!"