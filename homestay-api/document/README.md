# Homestay API - Tài liệu Triển khai

## Tổng quan

Homestay API là một ứng dụng Laravel được xây dựng trên framework Apiato, sử dụng Docker để containerization và AWS RDS làm database chính.

## Cấu trúc thư mục

```
homestay-api/
├── app/
│   ├── Containers/          # Các module nghiệp vụ
│   └── Ship/               # Core framework components
├── config/                 # Configuration files
├── database/              # Migrations & Seeders
├── docker/                # Docker configuration
├── document/              # Documentation (thư mục này)
├── public/                # Public assets
├── resources/             # Views, assets
├── routes/                # API routes
├── storage/              # File storage
├── tests/                # Test files
└── vendor/               # Dependencies
```

## Công nghệ sử dụng

- **Framework**: Laravel + Apiato
- **Database**: AWS RDS MySQL 8.0.42
- **Containerization**: Docker
- **Authentication**: Laravel Passport
- **Authorization**: Spatie Permission
- **Cache**: Redis

## Liên kết tài liệu

1. [Hướng dẫn Cài đặt](./installation.md)
2. [Cấu hình Docker](./docker-setup.md)
3. [Cấu hình Database](./database-setup.md)
4. [API Documentation](./api-documentation.md)
5. [Triển khai Production](./production-deployment.md)
6. [Troubleshooting](./troubleshooting.md)

## Thông tin liên hệ

- **Project**: Homestay API
- **Framework**: Apiato (Laravel-based)
- **Version**: PHP 8.x, Laravel 11.x
