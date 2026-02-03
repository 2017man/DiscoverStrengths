# AI 后端开发提示词

> 用于向 AI（如 Cursor、ChatGPT、Claude 等）提供后端开发任务时，可复制以下内容作为上下文与指令。

---

## 一、项目背景

项目名称：**《发现你的天赋优势》**，H5 测评小程序（MBTI 职业性格等）。前端为 uni-app，后端需提供 REST API。技术栈：**Laravel + dcat-admin + MySQL 5.7**。

---

## 二、必读文档（请优先阅读）

在项目根目录 `docs/` 下有以下文档，请按需阅读：

| 文档 | 路径 | 用途 |
|------|------|------|
| 接口文档 | `docs/接口文档.md` | 所有 API 的路径、参数、响应格式，**必须严格按此实现** |
| 后端开发文档 | `docs/后端开发文档.md` | 业务逻辑、计分规则、数据库表对应关系、PDF 生成指南 |
| 数据库设计 | `docs/数据库设计.md` | 表结构、MBTI 测试逻辑、评分规则 |
| 建表脚本 | `docs/database/建表脚本.sql` | MySQL 建表 SQL（10 张 strengths_ 表） |
| 前端接口对接检查记录 | `docs/前端接口对接检查记录.md` | 写死数据、无接口项、建议新增接口 |
| **后端开发任务说明** | `docs/后端开发任务说明.md` | **汇总后端全部任务 + 可直接复制的 AI 提示词** |

---

## 三、核心约定

1. **Base Path**：`/api/v1`
2. **响应格式**：成功 `{ "code": 200, "msg": "success", "data": { ... } }`；业务错误 `{ "code": 10000+, "msg": "错误说明" }`
3. **表名**：统一以 `strengths_` 为前缀，如 `strengths_test_types`、`strengths_test_answer`、`strengths_test_results_records`
4. **关联字段**：用 `test_type`（存 code，如 MBTI），不用 `test_type_id`
5. **维度代码**：与数据库一致，如 `E-I`、`S-N`、`T-F`、`J-P`（不是 `EI`、`SN`）

---

## 四、需实现或更新的接口清单

请根据 `docs/接口文档.md` 逐项实现：

| 接口 | 路径 | 说明 |
|------|------|------|
| 站点配置 | `GET /config/site` | 返回 stats_count、stats_date、qrcode_wechat、qrcode_community；前端已对接 |
| 测试类型列表 | `GET /test-types/list` | 返回 strengths_test_types，含 price、is_available |
| 测试类型详情 | `GET /test-types/detail?code=MBTI` | 说明页用 |
| MBTI 题目列表 | `GET /mbti/questions` | 题目 + 选项（从 strengths_test_question_options 组装）+ sections |
| MBTI 提交答案 | `POST /mbti/submit` | 计分、写入 strengths_test_results_records，返回 result_id、result_code、is_paid |
| 报告详情 | `GET /mbti/report?result_id=xxx` | 已付费返回完整结构化字段（见接口文档 4.2 表） |
| **PDF 下载** | `GET /mbti/report/pdf?result_id=xxx` | **已付费时返回 PDF 文件流，未付费 403**；前端已对接 |
| 报告接口 full=1 | `GET /mbti/report?result_id=xxx&full=1` | **测试环境**：带 full=1 时建议未付费也返回完整结构化字段 |
| 创建订单 | `POST /order/create` | body: result_id，返回 pay_url（易支付）；前端已对接 |
| 支付回调 | `POST /payment/epay/notify` | 易支付异步通知，验签后更新订单与 is_paid |

---

## 五、报告接口重点（易出错）

- **dimension_scores**：由 `strengths_test_results_records` 的 e_score～p_score 计算百分比，每维度 leftScore + rightScore = 100
- **dimension_sides**：来自 `strengths_test_dimension_sides`，8 条（E/I/S/N/T/F/J/P）
- **dimensions**：来自 `strengths_test_dimensions`，4 条（E-I/S-N/T-F/J-P）。`dimension_scope` 用于第 2 部分标题
- **dimensions_detail**：可选，含 leftCode/rightCode、leftScore/rightScore、leftName/rightName、leftOverview/rightOverview、leftFeatures/rightFeatures、leftKeywords/rightKeywords、leftExpression/rightExpression、leftMantra/rightMantra
- **strengths/weaknesses**：来自 `strengths_test_answer`，需为数组或可转为数组
- **traits_summary、traits、careers、typical_figures**：来自 `strengths_test_answer` 对应字段

---

## 六、可直接给 AI 的指令

**推荐**：复制 `docs/后端开发任务说明.md` 第六章「AI 提示词」全文，粘贴给 AI 即可。

或使用以下精简版：

```
请帮我完成《发现你的天赋优势》后端开发。Laravel + MySQL 5.7。先读 docs/接口文档.md、docs/后端开发文档.md、docs/database/建表脚本.sql。

必须实现：GET /config/site、GET /mbti/report（支持 full=1 测试）、GET /mbti/report/pdf、以及 test-types/list、mbti/intro、mbti/questions、mbti/submit、order/create、支付回调。报告接口 dimension_code 用 E-I/S-N/T-F/J-P，strengths/weaknesses 为数组。
```

---

## 七、写死/无接口项（需后端支持）

详见 `docs/前端接口对接检查记录.md`，摘要如下：

| 页面 | 写死/无接口项 | 建议后端 |
|------|---------------|----------|
| 首页 | stats_count、stats_date、qrcode_wechat、qrcode_community | 新增 `GET /config/site` 或扩展 `test-types/list` 返回 |
| 结果页 | 共鸣句 RESONANCE_SENTENCES（3 条随机） | `GET /mbti/report` 可增加 `resonance_sentence` 字段，或单独配置接口 |
| 报告页 | 温馨寄语、副标、qrcode | 报告接口已支持 qrcode；温馨寄语可保留前端或由接口返回 |
| 分享 | 无接口 | 若需分享卡片，可提供分享图生成接口 |

**海外支付（出国支付）**：当前易支付仅支持国内微信/支付宝。若需支持海外用户，可扩展 `POST /order/create` 支持 `pay_channel=stripe` 等，返回 Stripe Checkout URL。

---

## 八、补充说明

- 若 AI 未看到项目文件，可把 `docs/接口文档.md`、`docs/后端开发文档.md` 的关键部分复制粘贴到对话中
- PDF 生成推荐用 `barryvdh/laravel-dompdf`，详见 `docs/后端开发文档.md` 第五章「PDF 生成实现指南」
- 易支付对接见 `docs/易支付配置说明.md`
- 前端 PDF 下载已对接：调用 `GET /mbti/report/pdf?result_id=xxx`，需后端返回 PDF 文件流（Content-Type: application/pdf）
