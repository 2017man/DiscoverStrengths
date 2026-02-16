# PDF 生成说明（Browsershot）

## 技术方案

PDF 下载接口使用 **spatie/browsershot** 生成，基于 Puppeteer（无头 Chrome）渲染 HTML 转 PDF，相比 DomPDF 对中文和 CSS 支持更好。

## 环境要求

1. **Node.js**：需安装 Node.js（建议 v18+）
2. **Puppeteer**：需在项目目录执行 `npm install puppeteer` 安装

## 安装步骤

```bash
# 1. PHP 依赖（已通过 composer 安装）
composer require spatie/browsershot

# 2. Node 依赖
npm install puppeteer
```

## 接口说明

- **路径**：`GET /api/v1/mbti/report/pdf?result_id=xxx`
- **测试模式**：`?result_id=xxx&full=1` 可绕过付费校验
- **模板**：`resources/views/mbti/report-pdf.blade.php`

## 常见问题

### 1. 报错 "Could not find Chrome"

确保已执行 `npm install puppeteer`，Puppeteer 会下载 Chromium。

### 2. Windows 下 Node 找不到

若 Node 未加入 PATH，可在 `config/services.php` 或控制器中配置：

```php
Browsershot::html($html)
    ->setNodeBinary('C:\Program Files\nodejs\node.exe')
    ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
    // ...
```

### 3. 生产环境部署

- 服务器需安装 Node.js 和 Puppeteer
- 部分 Linux 需安装 Chromium 依赖：`apt-get install -y chromium-browser` 或使用 Puppeteer 自带的 Chromium
