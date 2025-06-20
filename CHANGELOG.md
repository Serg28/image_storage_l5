# Changelog

All notable changes to this project will be documented in this file.

## [2.0.0] - 2024-06-20

### Changed
- **BREAKING:** Migrated namespace from `Vis\ImageStorage` to `Linecore\ImageStorage`
- **BREAKING:** Changed table names from `vis_*` to `linecore_*` prefix
- **BREAKING:** Updated asset publication path from `packages/vis/image-storage` to `packages/linecore/image-storage`
- Updated package name from `vis/artur_image_storage_l5` to `linecore/image-storage-laravel`
- Upgraded Laravel compatibility to support versions 10.x, 11.x, and 12.x
- Updated PHP requirement to 8.1+
- Replaced deprecated `vis/curl_client_l5` dependency with `guzzlehttp/guzzle`
- Replaced deprecated `vis/optimization_img` with `intervention/image`
- Modernized HTTP client implementation in VideoAPI classes
- Improved service provider with better Laravel compatibility

### Added
- Automatic data migration from old `vis_*` tables to new `linecore_*` tables
- MIT License with original author attribution
- Comprehensive migration guide in README
- Modern Guzzle HTTP client implementation
- Custom image optimization using Intervention Image
- Enhanced error handling in API classes

### Removed
- Dependency on `vis/curl_client_l5`
- Dependency on `vis/optimization_img`
- Support for Laravel versions below 10.x
- Support for PHP versions below 8.1

### Fixed
- Compatibility issues with modern Laravel versions
- Deprecated method usage in HTTP clients
- Service provider registration for auto-discovery

### Migration Guide
For users migrating from `vis/artur_image_storage_l5`:

1. Update composer.json to use `linecore/image-storage-laravel`
2. Replace all `Vis\ImageStorage` namespace imports with `Linecore\ImageStorage`
3. Run `php artisan migrate` to automatically migrate data
4. Update any hardcoded asset paths from `packages/vis/image-storage` to `packages/linecore/image-storage`

## [1.x] - Previous versions
See original package `vis/artur_image_storage_l5` for version history prior to the namespace migration.