
                                <div style="display: flex; flex-wrap: wrap; gap: 2.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); font-size: 1.1rem; color: #94a3b8; padding-left: 1.25rem;">
                                  <div><strong style="color: #38bdf8;">Pembuat (Author):</strong> <span style="color: #e2e8f0;">{{ $item->author ?: 'Sistem' }}</span></div>
                                  <div><strong style="color: #38bdf8;">Dibuat (Created at): </strong> <span style="color: #e2e8f0;">{{ $item->created_at ? $item->created_at->locale('id')->translatedFormat('l, d F Y H:i') : 'Sistem' }}</span></div>
                                  <div><strong style="color: #38bdf8;">Pengubah Terakhir (Last Modified Author):</strong><span style="color: #e2e8f0;">{{ $item->last_modified_by ?: 'Sistem' }}</span></div>
                                  <div><strong style="color: #38bdf8;">Terakhir Diubah (Last Modified):</strong> <span style="color: #e2e8f0;">{{ $item->updated_at ? $item->updated_at->locale('id')->translatedFormat('l, d F Y H:i') : 'Sistem' }}</span></div>
                                </div>