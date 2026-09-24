# Architecture Decision Records (ADR)

Folder ini mendokumentasikan keputusan arsitektural penting (*Architectural Decisions*) yang telah disepakati dan diimplementasikan pada proyek **Web Inventory (InventarisPro)**. 

Tujuan ADR adalah memberikan **konteks historis (*context grounding*) yang permanen** bagi developer manusia maupun AI Agent (Antigravity, Cursor, Claude Code, dll.) agar keputusan teknis masa lalu dipahami alasannya dan tidak dirombak secara serampangan.

---

## Format & Template ADR

Setiap berkas ADR baru wajib mengikuti konvensi penamaan:
`XXXX-judul-keputusan-singkat.md` (misal: `0004-multi-warehouse-schema.md`)

Struktur standar ADR:
1. **Title & Status**: `[Nomor] - [Judul Keputusan]` (Status: `Proposed` | `Accepted` | `Deprecated` | `Superseded by ADR-XXXX`)
2. **Context & Problem Statement**: Latar belakang masalah bisnis / teknis yang memicu perlunya keputusan.
3. **Decision Drivers**: Faktor penentu (keamanan, performa, skalabilitas, maintainability, deadline).
4. **Considered Options**: Alternatif yang sempat dipertimbangkan beserta alasan penolakannya.
5. **Decision Outcome**: Solusi yang dipilih dan detail arsitekturalnya.
6. **Consequences & Trade-offs**: Dampak positif, dampak negatif, dan mitigasi risiko.

---

## Daftar Rekaman Keputusan (ADR Index)

| ID | Judul Keputusan | Status | Tanggal | Dampak Utama |
| :---: | :--- | :---: | :---: | :--- |
| **[ADR-0001](0001-adopt-layered-architecture.md)** | Penerapan Layered Architecture (Service-Repository-DTO) | `Accepted` | 2026-09-23 | Menghapus Fat Controller; memisahkan aturan bisnis dari HTTP layer |
| **[ADR-0002](0002-pessimistic-locking-for-stock-concurrency.md)** | Pessimistic Locking (`FOR UPDATE`) pada Mutasi Stok | `Accepted` | 2026-09-23 | Menjamin konsistensi stok atomik dan mencegah *race condition* |
| **[ADR-0003](0003-rbac-and-centralized-user-management.md)** | Implementasi RBAC 2-Tingkat & Penutupan Registrasi Publik | `Accepted` | 2026-09-24 | Melindungi data valuasi modal; kontrol terpusat akun staf oleh Admin |
| **[ADR-0004](0004-enterprise-folder-structure-and-api-partitioning.md)** | Penataan Folder Modular & Partisi Delivery Mechanism (Web vs API) | `Accepted` | 2026-09-24 | Memisahkan Web UI & REST API V1; layer Domain Exceptions & Reusable UI |
